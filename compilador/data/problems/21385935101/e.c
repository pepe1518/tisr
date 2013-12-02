#include <stdio.h>
#include <stdlib.h>

int main() {
    int i = 0;
    int n = 10000000;

    int *c;
    c = (int *) malloc (sizeof(int) * n);

    c[0] = 0;
    c[1] = 1;

    for (i = 2; i < n; i++) {
        c[i] = c[i - 2] + c[i - 1];
    }

    for (i = 0; i < n; i++) {
        printf("%d\n", c[i]);
    }

    return 0;
}

