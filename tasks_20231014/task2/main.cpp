#include <iostream>
#include <cmath>

// Code written as part of PJWSTK lessons
// Task 2: Amicable number pairs within range

int getDivisorSum(int n) {
    // Excluding the number itself. Quite an optimized algorithm.
    int result = 0;
    for (int i = 1; i <= std::sqrt(n); i++) {
        if (n % i == 0) {
            // This collects numbers from 1 to sqrt of that number, both inclusive
            result += i;
            // And this collects numbers from sqrt of that number up to that number, both exclusive
            if (i != 1 && i * i != n)
                result += n / i;
        }
    }
    return result;
}

int main() {
    int a, b;
    std::cout << "Podaj poczatek zakresu: ";
    std::cin >> a;
    std::cout << "Podaj koniec zakresu: ";
    std::cin >> b;

    for (int i = a; i <= b; i++) {
        int ds = getDivisorSum(i);
        if (i < ds && getDivisorSum(ds) == i) {
            std::cout << i << ", " << ds << std::endl;
        }
    }

    return 0;
}