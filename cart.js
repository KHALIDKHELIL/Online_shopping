const product = [
    {
        // id: 0,
        image: 'image/z-flip.webp',
        title: 'Z Flip Foldable Mobile',
        price: 120,
    },
    {
        // id: 1,
        image: 'image/airpods-pro.jpg',
        title: 'Air Pods Pro',
        price: 60,
    },
    {
        // id: 2,
        image: 'image/dslr-camera.jpg',
        title: '250D DSLR Camera',
        price: 230,
    },
    {
        // id: 3,
        image: 'image/iphone14.jpg',
        title: 'Smart phone iphone14',
        price: 100,
    },{
        // id: 4,
        image: 'image/mhd.jpg',
        title: 'MHD Camera',
        price: 230,
    },
    {
        // id: 5,
        image: 'image/iphone12.jpg',
        title: 'iPhones 12',
        price: 100,
    }
,
{
    // id: 0,
    image: 'image/z-flip.webp',
    title: 'Z Flip Foldable Mobile',
    price: 120,
},
{
    // id: 1,
    image: 'image/airpods-pro.jpg',
    title: 'Air Pods Pro',
    price: 60,
},
{
    // id: 2,
    image: 'image/dslr-camera.jpg',
    title: '250D DSLR Camera',
    price: 230,
},
{
    // id: 3,
    image: 'image/iphone14.jpg',
    title: 'Smart phone iphone14',
    price: 100,
},{
    // id: 4,
    image: 'image/mhd.jpg',
    title: 'MHD Camera',
    price: 230,
},
{
    // id: 5,
    image: 'image/iphone12.jpg',
    title: 'iPhones 12',
    price: 100,
},{
    // id: 0,
    image: 'image/z-flip.webp',
    title: 'Z Flip Foldable Mobile',
    price: 120,
},
{
    // id: 1,
    image: 'image/airpods-pro.jpg',
    title: 'Air Pods Pro',
    price: 60,
},
{
    // id: 2,
    image: 'image/dslr-camera.jpg',
    title: '250D DSLR Camera',
    price: 230,
},
{
    // id: 3,
    image: 'image/iphone14.jpg',
    title: 'Smart phone iphone14',
    price: 100,
},{
    // id: 4,
    image: 'image/mhd.jpg',
    title: 'MHD Camera',
    price: 230,
},
{
    // id: 5,
    image: 'image/iphone12.jpg',
    title: 'iPhones 12',
    price: 100,
},{
    // id: 0,
    image: 'image/z-flip.webp',
    title: 'Z Flip Foldable Mobile',
    price: 120,
},
{
    // id: 1,
    image: 'image/airpods-pro.jpg',
    title: 'Air Pods Pro',
    price: 60,
},
{
    // id: 2,
    image: 'image/dslr-camera.jpg',
    title: '250D DSLR Camera',
    price: 230,
},
{
    // id: 3,
    image: 'image/iphone14.jpg',
    title: 'Smart phone iphone14',
    price: 100,
},{
    // id: 4,
    image: 'image/mhd.jpg',
    title: 'MHD Camera',
    price: 230,
},
{
    // id: 5,
    image: 'image/iphone12.jpg',
    title: 'iPhones 12',
    price: 100,
},{
    // id: 0,
    image: 'image/z-flip.webp',
    title: 'Z Flip Foldable Mobile',
    price: 120,
},
{
    // id: 1,
    image: 'image/airpods-pro.jpg',
    title: 'Air Pods Pro',
    price: 60,
},
{
    // id: 2,
    image: 'image/dslr-camera.jpg',
    title: '250D DSLR Camera',
    price: 230,
},
{
    // id: 3,
    image: 'image/iphone14.jpg',
    title: 'Smart phone iphone14',
    price: 100,
},{
    // id: 4,
    image: 'image/mhd.jpg',
    title: 'MHD Camera',
    price: 230,
},
{
    // id: 5,
    image: 'image/iphone12.jpg',
    title: 'iPhones 12',
    price: 100,
},

    {
        // id: 6,
        image: 'image/iphone14.jpg',
        title: 'Smart phone iphone14',
        price: 100,
    },{
        // id: 7,
        image: 'image/mhd.jpg',
        title: 'MHD Camera',
        price: 230,
    },
    {
        // id: 8,
        image: 'image/iphone12.jpg',
        title: 'iPhones 12',
        price: 100,
    }

];

 

const categories = [...new Set(product.map((item)=>
    {return item}))]
  
    document.getElementById('Input').addEventListener('keyup', (e) => {
        const searchData = e.target.value.toLowerCase();
        const filteredData = categories.filter((item) => {
            return (
                item.title.toLowerCase().includes(searchData)
            )
        })
        displayItem(filteredData);
    });

    



    const displayItem = (items) => {
   
    let i=0;
document.getElementById('root').innerHTML = items.map((item)=>
{
    var {image, title, price} = item;
    return(
        `<div class='box' style="position:relative; margin-top: 100px;
        margin-bottom:0px;
    ">
            <div class='img-box'>
                <img class='images' src=${image}></img>
            </div>
        <div class='bottom'>
        <p>${title}</p>
        <h2 >$ ${price}.00</h2>`+
        "<button class='button' onclick='addtocart("+(i++)+")'>Add to cart</button>"+
        `</div>

        <div class="dropdown">
        <button class="dropbtn">Detail  
  <i class="fa fa-caret-down"></i>
</button>
        <div class="dropdown-content">
            <div class="header">
                <h2 style="color: black;">Detals About this product</h2>
            </div>
            <p style="color: black;">Product name : ${title} <br>price:$ ${price}
                                       </p>
           
        </div>
    </div>


        </div>`
    )
}).join('')
};
displayItem(categories);


    
var cart =[];




window.onload = function() {
    if (localStorage.getItem("cart")) {
      cart = JSON.parse(localStorage.getItem("cart"));
      displaycart();
    }
  };







function addtocart(a) {
    cart.push({ ...categories[a] });
    localStorage.setItem("cart", JSON.stringify(cart));
    displaycart();
  }









function delElement(a){
    cart.splice(a, 1);
    displaycart();
}

function displaycart() {
    let j = 0,
        total = 0;
    document.getElementById("count").innerHTML = cart.length;
    if (cart.length == 0) {
        document.getElementById("cartItem").innerHTML = " Your cart is empty";
        document.getElementById("total").innerHTML = "$ " + 0 + ".00";
    } else {
        document.getElementById("cartItem").innerHTML = cart.map((items) => {
            var { image, title, price } = items;
            total = total + price;
            document.getElementById("total").innerHTML = "$ " + total + ".00";
            return (
                `<div class='cart-item' >
                <div class='row-img'>
                    <img class='rowimg' src=${image}>
                </div>
                <p style='font-size:12px;'>${title}</p>
                <h2 style='font-size: 15px;'>$ ${price}.00</h2>` +
                "<i class='fa-solid fa-trash'  onclick='delElement(" +
                j++ +
                ")'></i></div>"
            );
        }).join("");
    }

    // Set cookie with total value
    document.cookie = "total=" + total;
}



function deleteAllItems() {
    cart = [];
    localStorage.removeItem("cart");
    displaycart();
  }




















  