Typed Arrays for PHP
====================

[`olifanton/typed-arrays`](https://packagist.org/packages/olifanton/typed-arrays) is a Composer package which implements [ECMAScript Typed Arrays](http://www.ecma-international.org/ecma-262/6.0/#sec-typedarray-objects) (previously a Khronos Group standard) for PHP 8.

What's supported
----------------

* [`ArrayBuffer`](https://www.khronos.org/registry/typedarray/specs/latest/#5)
* [Typed array views](https://www.khronos.org/registry/typedarray/specs/latest/#7)
  * [`Uint8ClampedArray`](https://www.khronos.org/registry/typedarray/specs/latest/#7.1)
* [`DataView`](https://www.khronos.org/registry/typedarray/specs/latest/#8)

What's not supported
--------------------------

* [Correct type conversion rules](https://www.khronos.org/registry/typedarray/specs/latest/#3)
* [`Transferable`](https://www.khronos.org/registry/typedarray/specs/latest/#9) (useless in the context of PHP)
* 32-bit platforms

Author
------

Andrea Faulds <ajf@ajf.me>

License
-------

LGPLv3
