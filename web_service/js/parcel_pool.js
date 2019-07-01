/// *** SHOW POOLS ***///
/// REGISTER EVENT
document.getElementById('showpoolbtn').addEventListener('click', function (e) {
  document.getElementById('showpoolbtn').style.display = 'none';
  showPoolsComponent(e);
});

/// RENDER COMPOTENT
function showPoolsComponent(evt) {
  // console.log(evt); //show the evet of clicking like the orientation of the mouse,...
  document.getElementById('mainoutput').innerHTML =
  document.getElementById('showpoolscomponent').innerHTML;
  // var parseHTML = document.getElementById('showpoolscomponent').innerHTML;
  var selectFrom = document.getElementById("from");
  var selectTo = document.getElementById("to");
  // console.log(select);
  fetch("parcel_pool_fb.php?page=potentialpools.html")
  .then(function (response) {
          // console.log(response);
    response.json().then(function(data){
        // console.log(data);
        for (i=0; i<data[0].length ; i++) {
          var option = document.createElement("OPTION");
          var txt = document.createTextNode(data[0][i].source_city);
          option.appendChild(txt);
          option.setAttribute("value", data[0][i].source_city);
          selectFrom.insertBefore(option, selectFrom.lastChild);
          // source = data[0][i].sourceCity;
          // console.log(data[0][i].sourceCity);
          // console.log(source);
          // fromHTML += parseHTML.replace(/{{form}}/g, data[0][i].sourceCity);
        } // end of for loop : source
        for (i=0; i < data[1].length; i++) {
          var option = document.createElement("OPTION");
          var txt = document.createTextNode(data[1][i].destination_city);
          option.appendChild(txt);
          option.setAttribute("value", data[1][i].destination_city);
          selectTo.insertBefore(option, selectTo.lastChild);
        } // end of for loop : destination
    }); //end of .then(fundtion(data))
  }); //end of .then(fundtion(response))
}

/// FUNCTION
function showPools() {
  var dest = document.getElementById("to");
  var to = dest.options[dest.selectedIndex].text;
  var source = document.getElementById("from");
  var from = source.options[source.selectedIndex].text;
  var parseHTML = document.getElementById('poolslistcomponent').innerHTML;
  // console.log(from);
  // console.log(to);

  // var pool = (JSON.stringify({
  //   "from": source,
  //   "to": dest,
  // }));


  if (from !== "From" && to !== "To") {
    fetch("parcel_pool_fb.php?page=showpools.html",
      // &from='+ source +'&to='+dest
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          "from": from,
          "to": to
        })
      }
    ).then(function (response) {
      // console.log(response);
      response.json().then(function(data) {
        console.log(data);
        console.log(data.length);
        // console.log(Object.keys(data).length); /// for json object
        // console.log(data.length); ///for json array
        console.log(data[0].source_city);
        // console.log(Object.keys(data[0]).length);
        if (data.length > 0) {
          var newpool = [];
          var outHTML = '';
          for (i=0; i < data.length; i++) {
            // console.log(data[i]);

              // for (j in data[i]) {
              // newpool += j + ' : ' + data[i][j] + '<br>';
              newpool[i] = '<div>' + data[i].id + ' From: ' + data[i].source_city + '  To: ' + data[i].destination_city
              + '<button id="choosebtn" value =' + data[i].id + ' onclick="showAddParcelComponent(' + data[i].id + ')">Add Your Parcel To This Pool</button>'
              // + '<button id="choosebtn" value =' + data[i].poolId + ' onclick="addParcel(' + data[i].poolId + ')">Add Your Parcel To This Pool</button>'
              ;
            // } //end of for[j] loop
            // console.log(newpool[i]);
            outHTML += parseHTML.replace(/{{pools}}/g, newpool[i]);
            // console.log(outHTML);
          } //end of for[i] loop
          // console.log(outHTML);
          // document.getElementById('pools').innerHTML = newpool;
          document.getElementById('result').innerHTML = outHTML;
        } else { // end of if condition for existed pool
          console.log(data.error);
          document.getElementById('result').textContent = data.error;
        }
      }); //end of .then(fundtion(data))
    }); //end of .then(fundtion(response))
  } else { //end of if statement for select from and to
      console.log("please select both source and destination.");
      document.getElementById('result').textContent = "please select both source and destination.";
  }
}



/// *** ADD PARCEL ***///
/// RENDER COMPOTENT
function showAddParcelComponent(evt) {
  document.getElementById('result').innerHTML='';
  localStorage.setItem('poolId',evt);
  if (localStorage.getItem('userId')) {
    // We have items
    document.getElementById('mainoutput').innerHTML =
    document.getElementById('addparcelcomponent').innerHTML;
    // console.log(evt);
    fetch("parcel_pool_fb.php?page=chosenpool.html&id=" + evt)
    .then(function (response) {
      response.json().then(function(data){
        // console.log(data);
        document.getElementById('from').value = data.source;
        document.getElementById('to').value = data.destination;
        // for (i=0; i<data.length; i++) {
        // }
      }); // end of .then(function(data)
    }); // end of .then(function (response)
  } else {
    // No items
    // if (document.getElementById('weight').value !== '') {
    //   var weight = document.getElementById('weight').value;
    //   localStorage.setItem('weight', weight);
    // }
    showLoginComponent();
  }
}

/// FUNCTION
function addParcel(){
  // console.log(poolId);
//     document.getElementById('pools').style.display = 'none';
//     document.getElementById('addparcelform').style.display = 'block';
//     console.log(poolId);
  var weight = document.getElementById("weight").value;
  if (weight !== "") {
    console.log(weight);

    fetch ('parcel_pool_fb.php?page=addparcel.html',
  //   // &id=' + poolId , 
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      // Create JSON string of registration form field
      body: JSON.stringify({
        "weight": weight,
      })
    })
    .then (function(response){
      response.json().then (function(data){
      console.log(data);
//           // var newpool = '';
//           // for (i=0; i < data.length; i++) {
//           //     newpool += '<div>' + data[i].poolId + ' From: ' + data[i].sourceCity + '  To: ' + data[i].destinationCity
//           //     + '<button class="choose" value =' + data[i].id + ' onclick="addParcel(' + data[i].id + ')">Add Your Parcel To This Pool</button>';
//           // }
//           document.getElementById('result').innerHTML = data.msg;
    
      });
    });
    document.getElementById('mainoutput').innerHTML = ('your parcel is added successfully!');

  } else {
    console.log("please fill all inputs");
  }
}



/// *** LOGIN - LOGOUT ***///
/// REGISTER EVENT
document.getElementById('loginbtn').addEventListener('click', function (e) {
  showLoginComponent(e);
});

/// RENDER COMPOTENTS
function showLoginComponent(evt) {
  document.getElementById('mainoutput').innerHTML =
  document.getElementById('logincomponent').innerHTML;
  if (localStorage.getItem('email')) {
    document.getElementById('email').value=localStorage.getItem('email');
  }
}

/// FUNCTION
function login(){
  var email = document.getElementById('email').value;
  var pass = document.getElementById('pass').value;
  // console.log(email);
  // console.log(pass);
  fetch("parcel_pool_fb.php?page=login.html",
    {
    method: "POST",
    headers: {
      "Content-Type" : "application/json"
    },
    body: JSON.stringify ({
      "email" : email,
      "pass": pass
    }) 
    }
  ).then(function(response){
    // console.log(response);
    response.json().then(function(data) {
      console.log(data);
      // console.log(Object.keys(data).length);
      // var user = '';
      // for (i=0; i<data.length; i++) {
      //   user += data[i].loginId;
      // }
      // let key = 'userId';
      localStorage.setItem('userId', data.msg);
      document.getElementById('result').innerHTML = localStorage.getItem('userId');
      if (localStorage.getItem('poolId')) {
        var poolId = localStorage.getItem('poolId');
        showAddParcelComponent(poolId);
      }
// data.msg;
    }); //end of .then(fundtion(data))
  }); //end of .then(fundtion(response))
} // end of login function


/// LOG OUT
document.getElementById('logoutbtn').addEventListener('click', function () {
  if (localStorage.length > 0 ) {
    localStorage.clear();
    // localStorage.removeItem('userId');
  }
});



/// *** SIGN UP ***///
/// REGISTER EVENTS
document.getElementById('signupbtn').addEventListener('click', function (e) {
  showsignupcomponent(e);
});


/// RENDER COMPOTENTS
function showsignupcomponent(evt) {
  document.getElementById('mainoutput').innerHTML =
  document.getElementById('signupcomponent').innerHTML;
}

/// FUNCTION
function signUp() {
  var fname = document.getElementById('fname').value;
  var lname = document.getElementById('lname').value;
  var email = document.getElementById('email').value;
  var pass = document.getElementById('pass').value;

  if ( fname !== "" && lname !== "" && email !== "" && pass !== "") {
    // console.log(fname + lname + email + pass);
    fetch ('parcel_pool_fb.php?page=singup.html',
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
        "firstname": fname,
        "lastname": lname,
        "email": email,
        "pass": pass
        })
      }
    ).then(function(response){
      response.json().then(function(data){
        console.log(data);
      }); ///end of .then(function(data)
    }); ///end of .then(function(response)
    localStorage.setItem('email',email);
    showLoginComponent();

  } else { 
    console.log("please fill all inputs");
  }
}









// function showpools(){
//   console.log(searchForPools.value);
// }


// document.getElementById("showpools").addEventListener("click", function() {
//   // showPools();
//   // var dest = document.getElementById("to");
//   // var to = dest.options[dest.selectedIndex].text;
//   console.log("hi");
// });


// function login() {
//     fetch ('parcel_pool.php?page=login.html' , {
//         method: "POST",
//         headers : {
//             "Content-Type" : "application/json" ,
//         },
//         body: JSON.stringify({
//             email: document.getElementById('email').value,
//             pass: document.getElementById('pass').value,
//         })
//     })
//     .then (function(response){
//         response.json()
//         .then (function(data){

//         })
//     })

// }

// function firstpage() {
//     document.getElementById('addparcelform').style.display = 'none';
// }

 //end of function showPools()








