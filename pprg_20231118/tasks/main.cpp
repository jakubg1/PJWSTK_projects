#include <iostream>
#include <cmath>

// Code written as part of PJWSTK lessons
// More function tasks

const double PI = 3.14159265358979323846;

// Returns 1 if the given number is a cube number, 0 otherwise.
int isCubeNumber(int n) {
    int i = 0;
    while (abs(pow(i, 3)) <= abs(n)) {
        if (pow(i, 3) == n)
            return 1;
        
        if (n > 0)
            i++;
        else
            i--;
    }
    return 0;
}

// Calculates the Newton symbol of two numbers. Iterative version.
long long newtonSymbolIter(int n, int k) {
    long long s = 1;
    // We could do it like this, but the S variable doesn't have that much capacity!
    //for (int i = 2; i <= n; i++)
    //    s *= i;
    //for (int i = 2; i <= k; i++)
    //    s /= i;
    // Instead, we will multiply and divide in alternating turns.
    // Now, because n >= k, s will always remain 1 until i = k.
    // This means that we can start multiplying from k+1.
    for (int i = k + 1; i <= n; i++)
        s *= i;
    // And divide by the other term in the denominator.
    for (int i = 2; i <= n - k; i++)
        s /= i;
    return s;
}

// Calculates the Newton symbol of two numbers. Recursive version.
long long newtonSymbolRecur(int n, int k) {
    if (k == 0 || k == n)
        return 1;
    return newtonSymbolRecur(n - 1, k - 1) + newtonSymbolRecur(n - 1, k);
}

// Calculates the BCD of a number using the Euclides algorithm. Iterative version.
int biggestCommonDivisorIter(int a, int b) {
    while (a != b) {
        if (a > b)
            a -= b;
        else
            b -= a;
    }
    return a;
}

// Calculates the BCD of a number using the Euclides algorithm. Recursive version.
int biggestCommonDivisorRecur(int a, int b) {
    if (a > b)
        return biggestCommonDivisorRecur(a - b, b);
    else if (a < b)
        return biggestCommonDivisorRecur(a, b - a);
    else
        return a;
}

// The following functions show how function overloading works.
double squareOrRectangleArea(double a) {
    return a * a;
}

double squareOrRectangleArea(double a, double b) {
    return a * b;
}

double circleOrTriangleArea(double r) {
    return PI * r * r;
}

double circleOrTriangleArea(double a, double h) {
    return a * h / 2;
}



// Main function - entry point.
int main() {
    std::cout << "Zadanie 1." << std::endl;
    const int NUMBERS[] = {1, 9, 26, 8000, -216, 0};
    for (int i = 0; i < 6; i++) {
        if (isCubeNumber(NUMBERS[i]))
            std::cout << "Liczba " << NUMBERS[i] << " jest szescianem liczby calkowitej" << std::endl;
        else
            std::cout << "Liczba " << NUMBERS[i] << " nie jest szescianem liczby calkowitej" << std::endl;
    }

    std::cout << "Zadanie 2a." << std::endl;
    std::cout << "Symbol Newtona (6, 3) = " << newtonSymbolIter(6, 3) << " = " << newtonSymbolRecur(6, 3) << std::endl;
    std::cout << "Symbol Newtona (7, 1) = " << newtonSymbolIter(7, 1) << " = " << newtonSymbolRecur(7, 1) << std::endl;
    std::cout << "Symbol Newtona (7, 5) = " << newtonSymbolIter(7, 5) << " = " << newtonSymbolRecur(7, 5) << std::endl;
    std::cout << "Symbol Newtona (0, 0) = " << newtonSymbolIter(0, 0) << " = " << newtonSymbolRecur(0, 0) << std::endl;
    std::cout << "Wersja iteracyjna: Symbol Newtona (33, 20) = ";
    std::cout << newtonSymbolIter(33, 20) << std::endl;
    std::cout << "Wersja rekursyjna: Symbol Newtona (33, 20) = ";
    std::cout << newtonSymbolRecur(33, 20) << std::endl;

    std::cout << "Zadanie 2b." << std::endl;
    std::cout << "NWD(492, 420) = " << biggestCommonDivisorIter(492, 420) << " = " << biggestCommonDivisorIter(492, 420) << std::endl;
    std::cout << "NWD(103, 105) = " << biggestCommonDivisorIter(103, 105) << " = " << biggestCommonDivisorIter(103, 105) << std::endl;
    std::cout << "NWD(69, 5859852) = " << biggestCommonDivisorIter(69, 5859852) << " = " << biggestCommonDivisorIter(69, 5859852) << std::endl;
    std::cout << "NWD(1000, 5939200) = " << biggestCommonDivisorIter(1000, 5939200) << " = " << biggestCommonDivisorIter(1000, 5939200) << std::endl;
    // 46283 crashes, while 46284 doesn't give any indication as to which algorithm is faster. Oh well.
    std::cout << "Wersja iteracyjna: NWD(46284, 2004157820) = ";
    std::cout << biggestCommonDivisorIter(46284, 2004157820) << std::endl;
    std::cout << "Wersja rekursyjna: NWD(46284, 2004157820) = ";
    std::cout << biggestCommonDivisorRecur(46284, 2004157820) << std::endl;

    std::cout << "Zadanie 3-1." << std::endl;
    std::cout << "Pole kwadratu o boku 9: " << squareOrRectangleArea(9) << std::endl;
    std::cout << "Pole prostokata o wymiarach 9x5: " << squareOrRectangleArea(9, 5) << std::endl;
    std::cout << "Pole kola o promieniu 10: " << circleOrTriangleArea(10) << std::endl;
    std::cout << "Pole trojkata o podstawie 10 i wysokosci 3: " << circleOrTriangleArea(10, 3) << std::endl;

    return 0;
}