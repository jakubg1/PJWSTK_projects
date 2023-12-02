#include <iostream>

// Code written as part of PJWSTK lessons
// Task 3-2: Arithmetic and geometric series

void calculateSeries(double a, double r, int n) {
    std::cout << "Ciag arytmetyczny:" << std::endl;
    double sum = 0;
    double ac = a;

    for (int i = 0; i < n; i++) {
        if (i > 0)
            std::cout << " + ";
        std::cout << ac;
        sum += ac;
        ac += r;
    }
    std::cout << " = " << sum << std::endl;

    std::cout << "Ciag geometryczny:" << std::endl;
    sum = 0;
    ac = a;

    for (int i = 0; i < n; i++) {
        if (i > 0)
            std::cout << " + ";
        std::cout << ac;
        sum += ac;
        ac *= r;
    }
    std::cout << " = " << sum << std::endl;
}

int main() {
    double a, r;
    int n;
    std::cout << "Podaj pierwszy wyraz ciagu: ";
    std::cin >> a;
    std::cout << "Podaj roznice/iloraz wyrazow: ";
    std::cin >> r;
    std::cout << "Podaj liczbe elementow: ";
    std::cin >> n;

    calculateSeries(a, r, n);

    return 0;
}