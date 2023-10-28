#include <iostream>

// Code written as part of PJWSTK lessons
// Task 2: Guess a password!

int main() {
    std::string password = "minecraft";
    std::string guess;

    while (true) {
        std::cout << "Podaj haslo: ";
        std::cin >> guess;

        if (password.compare(guess) == 0) {
            std::cout << "Gratulacje! Zgadles haslo!" << std::endl;
            break;
        } else {
            std::cout << "Niestety, sprobuj jeszcze raz." << std::endl;
        }
    }

    return 0;
}