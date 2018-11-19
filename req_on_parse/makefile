APP=req_on_parse
SRC=$(APP).c
objects=$(SRC:.c=.o) 
CFLAGS=-O3 -m64
#CFLAGS=-O0 -g3
all:$(APP)
$(APP): $(objects)
	gcc -o$(APP) $(objects) -m64 -lcurl
$(APP).o: $(SRC)
	gcc -std=gnu99 -c -o$(APP).o $(CFLAGS) $(SRC)

clean:
	rm -f $(APP) $(objects)
