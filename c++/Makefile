CXX = g++
INCLUDE_DIR = ./include
CFLAGS = 
CXXFLAGS = -fpermissive -std=c++11 

demo : appcode_demo.o
	$(CXX) $(CFLAGS) $(CXXFLAGS) -I$(INCLUDE_DIR) -o appcode_demo appcode_demo.o $(shell curl-config --libs)

appcode_demo.o : appcode_demo.cpp
	$(CXX) $(CFLAGS) $(CXXFLAGS) -I$(INCLUDE_DIR) -c appcode_demo.cpp

.PHONY : clean
clean :
	-rm appcode_demo appcode_demo.o
