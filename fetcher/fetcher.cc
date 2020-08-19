//curl --data "fn=9280440300557546&fd=32947&fp=1257980715&n=1&s=702.41&t=15.08.2020+21%3A57&qr=0" https://proverkacheka.com/check/get

#include <iostream>
#include <memory>
#include <string>
#include <unistd.h>
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>
#include "api.h"

using namespace std;

int main(void) {
	try {
          unique_ptr<sql::Connection> con (get_driver_instance()->connect(SERVER, LOGIN, PASSWD));
	  con->setSchema(LOGIN);
	  unique_ptr<sql::Statement> stmt (con->createStatement());
	  unique_ptr<sql::ResultSet> res (stmt->executeQuery(
		"SELECT id, rawReceipt, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, totalSum, DATE_FORMAT(dateTime, '%d.%m.%Y+%H%%3A%i'), if(rawReceipt is Null or rawReceipt='', '0', '1') rawLoaded, if(exists(SELECT 1 FROM receipt_user u where u.user_id=r.user_id),1,0) u_acc_exists"
		"  FROM receipt r"
		"  WHERE NOT EXISTS ("
		"  	 SELECT 1 "
		"    FROM receipt_item i"
		"    WHERE i.receipt_id = r.id"
		"  ) AND (rawReceipt is Null or rawReceipt != 'the ticket was not found')"
		"  ORDER BY u_acc_exists DESC, id"));
	  while (res->next()) {
              cout << "id = " << res->getString(1) << endl;
              string answer;
              if  (getDataFromProverkaCheka(&answer, res->getString(3), res->getString(4), res->getString(5), res->getUInt(6), res->getString(7))) {
                  cout << answer << endl;
              }
              sleep(5);
	  }
	} catch (sql::SQLException &e) {
	  cout << "# ERR: SQLException in " << __FILE__;
	  cout << "(" << __FUNCTION__ << ") on line "
		 << __LINE__ << endl;
	  cout << "# ERR: " << e.what();
	  cout << " (MySQL error code: " << e.getErrorCode();
	  cout << ", SQLState: " << e.getSQLState() << " )" << endl;
	}

	return EXIT_SUCCESS;
}
