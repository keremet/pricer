APP=parser
objects=parser.o
CFLAGS=-O3 -m64
#CFLAGS=-O0 -g3
all:$(APP)
$(APP): $(objects)
	g++ -o$(APP) $(objects) -m64 -lmysqlcppconn

parser.o: parser.cc
	g++ -c -o$@ $(CFLAGS) -DSERVER=\"localhost\" -DLOGIN=\"pricer\" -DPASSWD=\"1\" parser.cc

clean:
	rm -f $(APP) $(objects)
