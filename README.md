## Bulls and Cows game

#### Nuvei test game

### Pre-requirements
- [Laravel v8.83](https://laravel.com/docs/8.x) is used (*php version 7.4.2*)
- numbers 1 and 8 should be next to each other if in use
- numbers 4 and 5 shouldn't be on even position *(in the implementation the position is counted from **0**)*

### Installation steps
#### With default configuration
The default host name is ```http:\\bulls-and-cows.test```.
- Clone the repository
- Rename ```.env.example``` to ```.env```
- Run ```composer install --no-dev```
- Make ```nuvei.sqlite``` file in the root of```database``` folder
  - run ```php artisan migrate```
- Or move the provided one (with some data in it) from ```storage``` folder

This configuration expects host name to be the default one ```http:\\bulls-and-cows.test``` 

### Changing default configuration
To change the default host name
- Clone the repository
- Rename ```.env.example``` to ```.env```
- Change ```APP_URL``` and ```MIX_URL``` variables to reflect to the proper host name
- Run ```composer install --no-dev```
- Make ```nuvei.sqlite``` file in the root of```database``` folder
    - run ```php artisan migrate```
- Or move the provided one (with some data in it) from ```storage``` folder
- run `npm install && npm run dev` to install dependencies and recompile js file to use proper host name

### Decisions
The game logic is released in the FE with use the of [Alpine.js](https://alpinejs.dev/). and [Tailwind](https://tailwindcss.com/). 
The idea behind this is BE and FE implementations to be used equally for this test realization.

### Tests
Most of the BE is covered with tests.
