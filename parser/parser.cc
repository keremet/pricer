#include <iostream>
#include <vector>
#include <string>
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>
#include <cppconn/prepared_statement.h>
#include "json.hpp"

using namespace std;

using json = nlohmann::json;

template<typename T>
bool contains(vector<T> v, T x){ return (find(v.begin(), v.end(), x) != v.end()); }

void createQueryParams(sql::PreparedStatement *stmt, const json j, vector<string> paramNames, vector<string> ignoreList) {
    for (int i=1; i <= paramNames.size(); i++)
        stmt->setNull(i, 0);

    for (const auto &prop : j.items()) {
        if (contains(ignoreList, prop.key()))
            continue;

        auto it = find(paramNames.begin(), paramNames.end(), prop.key());
        if (paramNames.end() == it) {
            cout << prop.key() << " not found\n";
        } else {
            cout << prop.key() << " found " << distance(paramNames.begin(), it) << " " << prop.value() << endl;
            unsigned int paramIdx = distance(paramNames.begin(), it) + 1;
            if (prop.value().is_number_integer())
                stmt->setInt(paramIdx, static_cast<int32_t>(prop.value()));
            else if (prop.value().is_number_float())
                stmt->setDouble(paramIdx, static_cast<double>(prop.value()));
            else if (prop.value().is_string())
                stmt->setString(paramIdx, static_cast<string>(prop.value()));
            else
                cout << "unknown type\n";
        }
    }
}

int parse(sql::Connection *con, string input, int id) {
    json j;
    try {
        j = json::parse(input);
    }
    catch(...) {
        cout << "JSON parse error\n";
        return 1;
    }

    {
        unique_ptr<sql::PreparedStatement> stmtU (con->prepareStatement(R"(
            UPDATE receipt 
             SET buyerAddress = ?, addressToCheckFiscalSign = ?,
                kktRegId = ?, user = ?, operationType = ?, 
                shiftNumber = ?, ecashTotalSum = ?, nds18 = ?, retailPlaceAddress = ?, 
                userInn = ?, taxationType = ?, cashTotalSum = ?, operator = ?, 
                senderAddress = ?, receiptCode = ?, nds10 = ?, requestNumber = ?, ndsNo = ?
             WHERE id = ?)"));

        createQueryParams(stmtU.get(), j, {"buyerAddress", "addressToCheckFiscalSign",
                 "kktRegId", "user", "operationType",
                 "shiftNumber", "ecashTotalSum", "nds18", "retailPlaceAddress",
                 "userInn", "taxationType", "cashTotalSum", "operator",
                 "senderAddress", "receiptCode", "nds10", "requestNumber", "ndsNo"}, 
                 {"dateTime", "fiscalDocumentNumber", "fiscalDriveNumber", "fiscalSign", "totalSum", "items", "fnsUrl", "metadata","postpaymentSum", "prepaymentSum", "protocolVersion", "counterSubmissionSum", "rawData", "retailPlace"});
        stmtU->setInt(19 /*position of ID*/, id);
        stmtU->executeUpdate();
    }
    cout << "-----------------------\n";
    if (!j.contains("items") || !j["items"].is_array()) {
        cout << "Array 'items' not found\n";
        return 1;
    }

    unique_ptr<sql::PreparedStatement> stmtI (con->prepareStatement(R"(
            INSERT INTO receipt_item (sum, nds10, name, quantity, price, nds18, ndsNo, receipt_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?))"));
    stmtI->setInt(8 /*position of receipt_id*/, id);
    for (auto item : j["items"]) {
        createQueryParams(stmtI.get(), item, {"sum", "nds10", "name", "quantity", "price", "nds18", "ndsNo"}
            , {"ndsSum", "ndsRate", "calculationTypeSign", "calculationSubjectSign"});
        stmtI->executeUpdate();
    }

    return 0;
}


int main(int c, char** args) {
    try {
      unique_ptr<sql::Connection> con (get_driver_instance()->connect(SERVER, LOGIN, PASSWD));
      con->setSchema(LOGIN);
      unique_ptr<sql::Statement> stmtS (con->createStatement());
      unique_ptr<sql::ResultSet> res (stmtS->executeQuery(
            R"(
                SELECT id, rawReceipt
                FROM receipt r
                WHERE rawReceipt IS NOT NULL AND rawReceipt != 'the ticket was not found'
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM receipt_item i
                        WHERE i.receipt_id = r.id
                    )
            )"
            ));
      while (res->next()) {
          cout << "id = " << res->getString(1) << endl;
          parse(con.get(), res->getString(2), res->getInt(1));
      }
    } catch (sql::SQLException &e) {
      cout << "# ERR: SQLException in " << __FILE__;
      cout << "(" << __FUNCTION__ << ") on line "
             << __LINE__ << endl;
      cout << "# ERR: " << e.what();
      cout << " (MySQL error code: " << e.getErrorCode();
      cout << ", SQLState: " << e.getSQLState() << " )" << endl;
    }

    return 0;
}
