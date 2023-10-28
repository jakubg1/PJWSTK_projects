#include <iostream>

// Code written as part of PJWSTK lessons
// Task 3: Rock Paper Scissors

int main() {
    const std::string SYMBOLS[] = {"kamien", "papier", "nozyce"};
    int playerSymbol, cpuSymbol;
    std::string playerGuess;

    while (true) {
        std::cout << "Kamien, papier czy nozyce? ";
        std::cin >> playerGuess;

        // Detect the correct input and convert it into a number.
        for (int i = 0; i < 3; i++) {
            if (SYMBOLS[i].compare(playerGuess) == 0) {
                playerSymbol = i;
                break;
            } else if (i == 2) {
                playerSymbol = -1;
                std::cout << "Nieprawidlowe haslo! Wpisz: \"kamien\", \"papier\", lub \"nozyce\"." << std::endl;
            }
        }
        
        if (playerSymbol == -1) {
            continue;
        }

        cpuSymbol = std::rand() % 3;
        std::cout << "Przeciwnik wybral " << SYMBOLS[cpuSymbol] << "!" << std::endl;
        /*      playerSymbol
                0   1   2
        cpu 0   D   W   L
        Sym 1   L   D   W
        bol 2   W   L   D
        */
        if (playerSymbol == cpuSymbol)
            std::cout << "Remis!" << std::endl;
        else if (playerSymbol == (cpuSymbol + 1) % 3)
            std::cout << "Wygrywasz!" << std::endl;
        else
            std::cout << "Przegrywasz..." << std::endl;
        
        std::cout << "Gramy jeszcze raz? (wpisz Y)";
        std::cin >> playerGuess;
        if (playerGuess.compare("Y") != 0)
            break;
    }

    return 0;
}