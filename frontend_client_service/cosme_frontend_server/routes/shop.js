var express = require('express');
var router = express.Router();

router.get('/', function (req, res, next) {
  res.render('shop/shop');
});

router.get('/products', function (req, res, next) {
  res.render('shop/productsList', {
    productsData
  });
});

router.get('/product', function (req, res, next) {
  res.render('shop/product', {
    productsData,
    productInfo
  });
});

const productsData = [{
  img: "/shop/product_image/03.jpg",
  src: "./product",
  name: "迷你天鵝絨唇膏",
  id: "WC-AB-001",
  price: "600",
  score: "2",
  commentsNum: "8",
}, {
  img: "/shop/product_image/04.jpg",
  src: "./product",
  name: "Women Cloth 1",
  id: "WC-AB-001",
  price: "58.00",
  score: "2",
  commentsNum: "8",
}, {
  img: "/shop/product_image/05.jpg",
  src: "./product",
  name: "Women Cloth 1",
  id: "WC-AB-001",
  price: "58.00",
  score: "2",
  commentsNum: "8",
}, {
  img: "/shop/product_image/06.jpg",
  src: "./product",
  name: "Women Cloth 1",
  id: "WC-AB-001",
  price: "58.00",
  score: "2",
  commentsNum: "8",
}]

const productInfo = {
  imgs: [
    "/shop/product_image/01-0.jpg",
    "/shop/product_image/01-1.jpg",
    "/shop/product_image/01-2.jpg",
    "/shop/product_image/01-3.jpg",
  ],
  name: "迷你天鵝絨唇膏",
  id: "WC-AB-001",
  price: "600",
  score: "2",
  commentsNum: "8",
}


module.exports = router;