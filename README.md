# Zing Design Boilerplate WordPress theme

## Initial setup

### Install Node Modules

```
[sudo] npm install
```

### Foundation update

```
cd fnd
foundation update
```

### Install front-end tools

- Font Awesome: Icon font
- Slick Carousel: Responsive content and image slider jQuery plugin

```
bower install https://github.com/FortAwesome/Font-Awesome.git
bower install https://github.com/kenwheeler/slick.git
```

### Grunt tasks

#### Process all JS and Sass

```
grunt
```

#### Watch client JS & Sass

```
grunt watch
```


#### Process Sass

```
grunt css
```

#### Copy icon fonts for Font Awesome and Slick

``` 
grunt copyFonts
```