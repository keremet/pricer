#include <iostream>
#include <string>
#include <curl/curl.h>
#include "api.h"

using namespace std;

static string to_sum(uint32_t i) {
        string s = "";
        for (int len = 0; i || len < 3; i /= 10, len++) {
                if (2 == len)
                        s.insert (s.begin(), '.');
                s.insert (s.begin(), (i%10) + 0x30);
        }
        return s;
}

bool getDataFromProverkaCheka(string *answer, const string fn, const string fd, const string fp, uint32_t sum, const string time) {
    CURL *curl = curl_easy_init();
    if (nullptr == curl) {
        cerr << "Ошибка инициализации cURL"  << endl;
        return false;
    }

    curl_easy_setopt(curl, CURLOPT_URL, "https://proverkacheka.com/check/get");
    curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_easy_setopt(curl, CURLOPT_POST, 1);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, answer);

    char curlErrorBuffer[CURL_ERROR_SIZE]; // буфер для сохранения текстовых ошибок
    curl_easy_setopt(curl, CURLOPT_ERRORBUFFER, curlErrorBuffer);

    const string urlPOST = "fn=" + fn + "&fd=" + fd + "&fp=" + fp + "&n=1&s=" + to_sum(sum) + "&t=" + time + "&qr=0";
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, urlPOST.c_str());

    typedef size_t(*CURL_WRITEFUNCTION_PTR)(const char*, size_t, size_t, string*);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, static_cast<CURL_WRITEFUNCTION_PTR>(
            [](const char* data, size_t size, size_t nmemb, string *buffer) -> size_t {
                size_t result = size * nmemb;
                buffer->append(data, result);
                return result;
            }
        ));

    CURLcode curlResult = curl_easy_perform(curl);
    curl_easy_cleanup(curl);

    if (curlResult != CURLE_OK) {
        cerr << "Ошибка(" << curlResult << "): " << curlErrorBuffer << endl;
        return false;
    }

    auto json_pos = answer->find("\"json\"");
    if (string::npos == json_pos) {
        cerr << "JSON не найден" << endl << answer << endl;
        return false;
    }

    auto bracket1_pos = answer->find('{', json_pos);
    if (string::npos == bracket1_pos) {
        cerr << "Открывающая скобка не найдена" << endl << answer << endl;
        return false;
    }
    answer->erase(0, bracket1_pos);

    int level = 1;
    size_t bracket2_pos = 0;
    for (int pos = 1; pos < answer->length(); pos++) {
        if ('{' == (*answer)[pos])
            level++;
        else if ('}' == (*answer)[pos] && (0 == --level)) {
            bracket2_pos = pos;
            break;
        }
    }
    if (0 == bracket2_pos) {
        cerr << "Закрывающая скобка не найдена" << endl << answer << endl;
        return false;
    }
    answer->erase(bracket2_pos + 1);
    return true;
}

/*
int main(int argc, char *argv[])
{
    string answer;
    if  (getDataFromProverkaCheka(&answer)) {
        cout << answer << endl;
    }

    return 0;
}
*/
