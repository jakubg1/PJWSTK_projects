#include <iostream>

// Code written as part of PJWSTK lessons
// Task 1: Arithmetic series: sum calculation

int main() {
    int a, r, n;
    std::cout << "Podaj pierwszy wyraz ciagu: ";
    std::cin >> a;
    std::cout << "Podaj roznice wyrazow: ";
    std::cin >> r;
    std::cout << "Podaj liczbe elementow: ";
    std::cin >> n;

    int total = 0;
    for (int i = 0; i < n; i++) {
        total += a;
        a += r;
    }
    std::cout << "Suma wyrazow tego ciagu to " << total << std::endl;

    return 0;
}