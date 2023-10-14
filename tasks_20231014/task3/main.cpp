#include <iostream>

// Code written as part of PJWSTK lessons
// Task 3: N-th Fibonacci number

int main() {
    int a;
    std::cout << "Podaj liczbe: ";
    std::cin >> a;

    // It will still give incorrect results after 100-something.
    // Needs some specialized math libraries, but we won't do that for now.
    long long x = 0;
    long long y = 1;
    long long z;
    for (int i = 0; i < a; i++) {
        z = x + y;
        x = y;
        y = z;
    }
    std::cout << a << ". liczba Fibonacciego to " << x << std::endl;

    return 0;
}