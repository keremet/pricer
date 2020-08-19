APP=fetcher
objects=api.o fetcher.o
CFLAGS=-O3 -m64
#CFLAGS=-O0 -g3
all:$(APP)
$(APP): $(objects)
	g++ -o$(APP) $(objects) -m64 -lcurl -lmysqlcppconn

api.o: api.cc api.h
	g++ -c -o$@ $(CFLAGS) api.cc

fetcher.o: fetcher.cc api.h
	g++ -c -o$@ $(CFLAGS) -DSERVER=\"localhost\" -DLOGIN=\"pricer\" -DPASSWD=\"1\" fetcher.cc

clean:
	rm -f $(APP) $(objects)

