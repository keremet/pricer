#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <unistd.h>
 
#include <curl/curl.h>

struct MemoryStruct {
  char *memory;
  size_t size;
};
 
static size_t
WriteMemoryCallback(void *contents, size_t size, size_t nmemb, void *userp){
  size_t realsize = size * nmemb;
  struct MemoryStruct *mem = (struct MemoryStruct *)userp;
 
  char *ptr = realloc(mem->memory, mem->size + realsize + 1);
  if(ptr == NULL) {
    /* out of memory! */ 
    printf("not enough memory (realloc returned NULL)\n");
    return 0;
  }
 
  mem->memory = ptr;
  memcpy(&(mem->memory[mem->size]), contents, realsize);
  mem->size += realsize;
  mem->memory[mem->size] = 0;
 
  return realsize;
}

CURLcode downloadToStruct(const char* url, struct MemoryStruct *chunk){  
	chunk->memory = malloc(1);  /* will be grown as needed by the realloc above */ 
	chunk->size = 0;    /* no data at this point */ 

	CURL *curl_handle = curl_easy_init();
	curl_easy_setopt(curl_handle, CURLOPT_URL, url);
	curl_easy_setopt(curl_handle, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);
	curl_easy_setopt(curl_handle, CURLOPT_WRITEDATA, (void *)chunk);
	curl_easy_setopt(curl_handle, CURLOPT_USERAGENT, "libcurl-agent/1.0");

	CURLcode res = curl_easy_perform(curl_handle);
	/* check for errors */ 
	if(res != CURLE_OK) {
		fprintf(stderr, "curl_easy_perform() failed: %s\n",
			curl_easy_strerror(res));
	}
	curl_easy_cleanup(curl_handle);
	return res;
}

int parseNumAndSpace(char** pos2line, int *id){
	*id = 0;
	do{
		if(!isdigit(**pos2line))
			return 0;

		*id = *id * 10 + (**pos2line - '0');
		(*pos2line)++;	
	}while(!isspace(**pos2line));
	(*pos2line)++;
	return 1;
}

int parseLine(char** pos2line, int *id, int *checked, int *rawLoaded){
	if(!parseNumAndSpace(pos2line, id))
		return 0;
	if(!parseNumAndSpace(pos2line, checked))
		return 0;
	if(!parseNumAndSpace(pos2line, rawLoaded))
		return 0;
	
	return 1;
}

int main(void) {
	curl_global_init(CURL_GLOBAL_ALL);

	struct MemoryStruct not_parsed;

	if( downloadToStruct("http://orv.org.ru/pricer/api/receipt/get_not_parsed.php", &not_parsed) == CURLE_OK ){
		printf( "%s\n"
				"sleep 5 sec\n", not_parsed.memory);
		sleep(5);
		int id, checked, rawLoaded;
		for( char* pos2line=not_parsed.memory; parseLine(&pos2line, &id, &checked, &rawLoaded); ){
			printf("Process %i - %i - %i\n", id, checked, rawLoaded);
			if(!checked){
				struct MemoryStruct check_out;
				char url[100];
				snprintf(url, sizeof(url), "http://orv.org.ru/pricer/api/receipt/check.php?id=%i", id);
				if( downloadToStruct(url, &check_out) != CURLE_OK)
					continue;
				printf("%s\n", check_out.memory);
				if(!strstr(check_out.memory, "Результат проверки: ''")){
					puts("Проверка завершилась неудачно. Пропускаем этот чек");
					free(check_out.memory);
					continue;
				}
				puts("Проверка ОК. sleep 5 sec");
				free(check_out.memory);
				sleep(5);
			}
			for(int i=0;(i<5) && !rawLoaded;i++){
				struct MemoryStruct raw_out;
				char url[100];
				snprintf(url, sizeof(url), "http://orv.org.ru/pricer/api/receipt/raw.php?id=%i", id);
				if( downloadToStruct(url, &raw_out) != CURLE_OK)
					continue;
				printf("%s\n", raw_out.memory);
				if(!strstr(raw_out.memory, "Данные из налоговой: ''"))
					rawLoaded = 1;
				printf((rawLoaded)?
					"Попытка %i. Извлечение данных удачно. sleep 5 sec\n":
					"Попытка %i. Извлечение данных неудачно. sleep 5 sec\n"
					, i+1);
				sleep(5);
				free(raw_out.memory);
			}
			
			struct MemoryStruct parse_out;
			char url[100];
			snprintf(url, sizeof(url), "http://orv.org.ru/pricer/api/receipt/parse.php?id=%i", id);
			if( downloadToStruct(url, &parse_out) != CURLE_OK)
				continue;
			puts(parse_out.memory);
			free(parse_out.memory);
			
			puts("sleep 5 sec");
			sleep(5);
		}
	}

	free(not_parsed.memory);
	curl_global_cleanup();
	return 0;
}
