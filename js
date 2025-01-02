<script>
      // Fungsi untuk login
      function handleLogin(event) {
        event.preventDefault(); // Cegah halaman reload

        // Ambil nilai username dan password
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        // Logika validasi login
        if (username === "admin" && password === "admin123") {
          alert("Login Berhasil sebagai Admin!");
          window.location.href = "equipment.html"; // Arahkan ke halaman admin
        } else if (username === "erika" && password === "erika") {
          alert("Login Berhasil!");
          window.location.href = "equipment.html"; // Arahkan ke halaman Equipment
        } else {
          alert("Username atau Password salah!");
        }
      }

      function redirectToPayment(itemId) {
        const selectedItem = item.find(item => item.id === itemId);
        if (selectedItem) {
          localStorage.setItem('selectedItem',JSON.stringify(selectedItem));
          window.location.href = 'Payment.html'; 
        }
      }

      function displayItems(filter = '') {
        const container = document.getElementById('itemContainer');
        container.innerHTML = '';
        const filteredItems = items.filter(item =>
            filter === '' || item.category.toLowerCase() === filter.toLowerCase()
        );

        filteredItems.forEach(item => {
            const div = document.createElement('div');
            div.className = 'item';
            div.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h3>${item.name}</h3>
                <p>${item.price}</p>
                <button onclick="redirectToPayment(${item.id})">Add to Cart</button>
            `;
            container.appendChild(div);
        });
      }

        window.onload = function() {
            const selectedItem = JSON.parse(localStorage.getItem('selectedItem'));
            if (selectedItem) {
                document.getElementById('itemName').innerText = selectedItem.name;
                document.getElementById('itemImage').src = selectedItem.image;
                document.getElementById('itemPrice').innerText = `Price: ${selectedItem.price}`;
            } else {
                document.body.innerHTML = '<p>No item selected. Go back to the equipment page.</p>';
            }
        };

    </script>
  </head>
  <body>
    <header>
      <div class="logo">Rent Mountain</div>
      <div class="menu">
        <a href="dashboard.html">Home</a>
        <a href="equipment.html">Equipment</a>
        <a href="payment.html">Payment</a>
        <a href="about.html">About</a>
        <a href="contact.html">Contact</a>
      </div>
    </header>
    <div class="form-container">
      <div class="form-box">
        <form id="login-form" onsubmit="handleLogin(event)">
          <h2>Log In</h2>
          <input type="text" id="username" placeholder="Username" required />
          <input type="password" id="password" placeholder="Password" required />
          <button type="submit">Log In</button>
          <div class="link">
            <p>Belum punya akun? <a href="signin.html">Sign In</a></p>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>