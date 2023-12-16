#include <iostream>
#include <cmath>

// Code written as part of PJWSTK lessons
// Task 2: People and management

// In order to make the program display the menu and select the data more than once,
// one of the options is to wrap the menu flow inside a while loop!
// Which is what is done here!

struct Person {
    std::string name;
    std::string surname;
    int age;
};

Person PEOPLE[] = {
    {"Grzegorz", "Brzeczyszczykiewicz", 50},
    {"Grzegorz", "Brzeczyszczykiewicz", 20},
    {"Piotr", "Kowalski", 26},
    {"Jan", "Kowalski", 25},
    {"Anna", "Maria Wesolowska", 55},
    {"Elon", "Musk", 40},
    {"Magda", "Gessler", 60},
    {"Maryla", "Rodowicz", 999},
    {"Anna", "Jantar", 53}
};

const int PEOPLE_LEN = 9;

void sortPeople(Person people[], int peopleLen) {
    // Bubble sorting will be used here.
    for (int i = 0; i < peopleLen - 1; i++) {
        for (int j = 0; j < peopleLen - i - 1; j++) {
            // We will be comparing people[j] with people[j + 1].
            Person p1 = people[j];
            Person p2 = people[j + 1];
            if (p1.surname.compare(p2.surname) > 0 || (p1.surname.compare(p2.surname) == 0 && (p1.name.compare(p2.name) > 0 || (p1.name.compare(p2.name) == 0 && p1.age > p2.age)))) {
                people[j] = p2;
                people[j + 1] = p1;
            }
        }
    }
}

void printPerson(Person people[], int i) {
    Person p = people[i];
    std::cout << i << ". " << p.name << " " << p.surname << " (" << p.age << ")" << std::endl;
}

void printPeople(Person people[], int peopleLen) {
    for (int i = 0; i < peopleLen; i++) {
        printPerson(people, i);
    }
}

void printPeople(Person people[], int peopleLen, int a, int b) {
    if (a > b || a < 0 || b > peopleLen - 1) {
        std::cout << "BLAD: podano zle indeksy!" << std::endl;
        return;
    }
    for (int i = a; i <= b; i++) {
        printPerson(people, i);
    }
}

void printPeopleNotOlderThan(Person people[], int peopleLen, int age) {
    for (int i = 0; i < peopleLen; i++) {
        if (PEOPLE[i].age <= age)
            printPerson(people, i);
    }
}

int askForChoice(std::string choices[], int n) {
    while (true) {
        for (int i = 0; i < n; i++) {
            std::cout << i + 1 << ") " << choices[i] << std::endl;
        }
        std::cout << " >>> ";
        int choice;
        std::cin >> choice;
        if (choice > 0 && choice <= n) {
            return choice - 1;
        }
        // otherwise, invalid input = prompt again
    }
}

int main() {
    std::cout << "Przed sortowaniem:" << std::endl;
    printPeople(PEOPLE, PEOPLE_LEN);
    sortPeople(PEOPLE, PEOPLE_LEN);
    std::cout << "Po sortowaniu:" << std::endl;
    printPeople(PEOPLE, PEOPLE_LEN);
    std::cout << "Po sortowaniu - wpisy od 4 do 7:" << std::endl;
    printPeople(PEOPLE, PEOPLE_LEN, 4, 7);
    std::cout << "Osoby nie starsze niz 50 lat:" << std::endl;
    printPeopleNotOlderThan(PEOPLE, PEOPLE_LEN, 50);

    int n;
    std::cout << std::endl << std::endl << std::endl;
    std::cout << "Podaj liczbe osob ktore chcesz utworzyc: ";
    std::cin >> n;

    Person people[n];
    for (int i = 0; i < n; i++) {
        std::cout << std::endl << "Osoba nr " << i + 1 << std::endl;
        std::cout << "Imie? ";
        std::cin >> people[i].name;
        std::cout << "Nazwisko? ";
        std::cin >> people[i].surname;
        std::cout << "Wiek? ";
        std::cin >> people[i].age;
    }
    
    while (true) {
        std::cout << std::endl;
        std::cout << "====================" << std::endl;
        std::cout << "Menu glowne" << std::endl;
        std::string choices[] = {"Pokaz wszystkich", "Pokaz zakres", "Wyjscie"};
        int choice = askForChoice(choices, 3);
        if (choice == 2)
            break; // exit the program
        
        int a = 0;
        int b = n - 1;
        if (choice == 1) {
            while (a < 0 || a >= n) {
                std::cout << "Zakres od nr: ";
                std::cin >> a;
            }
            while (b < a || b >= n) {
                std::cout << "Zakres do nr: ";
                std::cin >> b;
            }
        }
        std::string choices2[] = {"Sortuj", "Bez sortowania", "Powrot do menu"};
        int choice2 = askForChoice(choices2, 3);
        if (choice == 2)
            continue; // go back to menu

        // We are going to copy the table into here so that sorting it does not modify the original data.
        int resultLength = b - a + 1;
        Person peopleForResult[resultLength];
        for (int i = 0; i <= b - a; i++) {
            peopleForResult[i] = people[i + a];
        }
        if (choice2 == 0) // sorting
            sortPeople(peopleForResult, resultLength);
        
        std::cout << std::endl;
        std::cout << "====================" << std::endl;
        std::cout << "Wyniki wyszukiwania" << std::endl;
        printPeople(peopleForResult, resultLength);
        std::cout << std::endl;
    }

    return 0;
}