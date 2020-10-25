## Collatz Conjecture


Das Collatz-Conjecture ist ein einfach ausschauendes aber immernoch ungelöstes Mathe-Problem.  
Gegeben ist **n**, dann stellt sich die Frage, ob n immer 1 erreicht oder ob es eine Zahl gibt, bei der die Zerfallskette ins Unendliche springt.
Die Regeln:
- Ist **n** gerade, dann teile n durch 2
- Ist **n** ungerage, dann multipliziere n mit 3 und addiere 1

[More on this problem, the history and reasoning, and why it hasn't been solved yet: here](https://en.wikipedia.org/wiki/Collatz_conjecture)

So könnte der Code in PHP aussehen:
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

### Demo:
{%collatz-conjecture.html.twig%}
