## Collatz Conjecture


The collatz-conjecture is a simple-looking but still unsolved math problem.
Given any number **n**, will it always break down to 1 after applying the two rules?
The rules:
- If **n** is even, divide n by 2
- If **n** is odd, multiply by 3 and add 1

[More on this problem, the history and reasoning, and why it hasn't been solved yet: here](https://en.wikipedia.org/wiki/Collatz_conjecture)

This is how the rules above could look like in PHP:
```php

$n = 27;

while ($n !== 1) {

    if ($n % 2 === 0) {
    
        $n = $n / 2;
    
    } else {
    
        $n = 3 * $n + 1;
    
    }

}

```

### Try it out:
{%collatz-conjecture.html.twig%}
