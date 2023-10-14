#include <iostream>

// Code written as part of PJWSTK lessons
// Task 1: Prime numbers within range

bool isPrime(int n) {
    if (n < 2) {
        return false;
    }
    for (int i = 2; i <= n / 2; i++) {
        if (n % i == 0) {
            return false;
        }
    }
    return true;
}

int main() {
    int a, b;
    std::cout << "Podaj poczatek zakresu: ";
    std::cin >> a;
    std::cout << "Podaj koniec zakresu: ";
    std::cin >> b;

    for (int i = a; i <= b; i++) {
        if (isPrime(i)) {
            std::cout << i << std::endl;
        }
    }

    return 0;
}