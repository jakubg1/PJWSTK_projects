#include <iostream>
#include <cmath>

// Code written as part of PJWSTK lessons
// Exercise tasks: Functions

int a = 0;
int b = 0;

// Returns whether the number is even or not.
bool isEven(int n) {
    return n % 2 == 0;
}

// Prints whether the number is even or not.
void printIsEven(int n) {
    if (isEven(n))
        std::cout << "Liczba " << n << " jest parzysta." << std::endl;
    else
        std::cout << "Liczba " << n << " jest nieparzysta." << std::endl;
}

// Swaps the global integers A and B.
void swapAAndB() {
    int temp = a;
    a = b;
    b = temp;
}

// Returns true if the given number is a prime number.
bool isPrime(int n) {
    if (n < 2) {
        return false;
    }
    for (int i = 2; i <= std::sqrt(n); i++) {
        if (n % i == 0) {
            return false;
        }
    }
    return true;
}

// Prints all primes in a range from 1 to N.
void printPrimes1ToN(int n) {
    std::cout << "Liczby pierwsze w zakresie od 1 do " << n << ":" << std::endl;
    for (int i = 2; i <= n; i++) {
        if (isPrime(i)) {
            std::cout << " " << i;
        }
    }
    std::cout << std::endl;
}

// Returns the number B raised to the power of E using recursion.
int power(int b, int e) {
    if (e == 0) {
        return 1;
    } else if (e == 1) {
        return b;
    } else {
        return b * power(b, e - 1);
    }
}

// Returns a number in a range from 0 to 9 by summing all the number's digits over and over again.
int sumOfDigits(int n) {
    int sum = 0;
    while (n > 0) {
        sum += n % 10;
        n /= 10;
    }
    if (sum > 9) {
        return sumOfDigits(sum);
    }
    return sum;
}



// Main function - entry point.
int main() {
    std::cout << "Zadanie 1." << std::endl;
    printIsEven(10);
    printIsEven(3);
    printIsEven(0);
    printIsEven(-5);
    printIsEven(-10000);

    std::cout << "Zadanie 2." << std::endl;
    a = 5;
    b = -3;
    std::cout << "A wynosi " << a << " a B wynosi " << b << std::endl;
    swapAAndB();
    std::cout << "A wynosi " << a << " a B wynosi " << b << std::endl;

    std::cout << "Zadanie 3." << std::endl;
    printPrimes1ToN(50);
    printPrimes1ToN(500);

    std::cout << "Zadanie 4." << std::endl;
    std::cout << "5 ^ 2 = " << power(5, 2) << std::endl;
    std::cout << "8 ^ 1 = " << power(8, 1) << std::endl;
    std::cout << "17 ^ 0 = " << power(17, 0) << std::endl;
    std::cout << "3 ^ 6 = " << power(3, 6) << std::endl;
    std::cout << "1 ^ 1 = " << power(1, 1) << std::endl;
    std::cout << "0 ^ 5 = " << power(0, 5) << std::endl;
    std::cout << "1 ^ 5 = " << power(1, 5) << std::endl;
    std::cout << "-5 ^ 2 = " << power(-5, 2) << std::endl;
    std::cout << "-5 ^ 3 = " << power(-5, 3) << std::endl;

    std::cout << "Zadanie 5." << std::endl;
    std::cout << "402 -> " << sumOfDigits(402) << std::endl;
    std::cout << "9999 -> " << sumOfDigits(9999) << std::endl;
    std::cout << "502825 -> " << sumOfDigits(502825) << std::endl;
    std::cout << "0 -> " << sumOfDigits(0) << std::endl;

    return 0;
}