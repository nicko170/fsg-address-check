<label>
	Enter your address. See what happens. I'm not sure what happens, but it's not what I expected.<br/>
	<input type="text" id="nbn-address-search" class="address-search input" onkeyup="processChange()"/>
</label>

<div id="nbn-address-results" style="display: none">
	<h3>Address Results</h3>
	<ul id="nbn-address-results-list">

	</ul>
</div>

<div id="product-results" style="display: none">
</div>

<script>
	window.addressCheckCallback = function (apiResponse) {
		// This is going to be used to show the techs available for the address selected
		console.log(apiResponse);
	}

	function debounce(func, timeout = 300) {
		let timer;
		return (...args) => {
			clearTimeout(timer);
			timer = setTimeout(() => {
				func.apply(this, args);
			}, timeout);
		};
	}

	function searchInput() {
		// get value from input field
		var input = document.querySelector('#nbn-address-search').value;
		// if there are 3 or more characters, we want to search for the address

		if (input.length >= 6) {
			const form = new FormData();
			form.append('action', 'nbn_address_search');
			form.append('address', input);

			fetch('/wp-admin/admin-ajax.php', {
				method: 'POST',
				body: form,
			})
				.then(res => res.json())
				.then(function (data) {
					// show the results
					document.querySelector('#nbn-address-results').style.display = 'block';
					// clear the list
					document.querySelector('#nbn-address-results-list').innerHTML = '';
					document.querySelector('#product-results').innerHTML = '';
					// loop through the results
					data.forEach(function (item) {
						var li = document.createElement('li');
						var a = document.createElement('a');
						a.href = '#';
						a.innerText = item.id + ": " + item.formattedAddress;
						a.id = item.id;
						a.addEventListener('click', function (e) {
							e.preventDefault();
							// TODO: Search for products for this address.
							console.log('You clicked on ' + this.id);

							const form = new FormData();
							form.append('action', 'nbn_product_search');
							form.append('location_id', this.id);

							document.querySelector('#product-results').style.display = 'block';
							document.querySelector('#product-results').innerHTML = 'loading...';

							// fetch the product posts
							fetch('/wp-admin/admin-ajax.php', {
								method: 'POST',
								body: form,
							})
								.then(res => res.text())
								.then(function (data) {
									document.querySelector('#product-results').style.display = 'block';
									document.querySelector('#product-results').innerHTML = data;
								});


						});

						li.appendChild(a);
						document.querySelector('#nbn-address-results-list').appendChild(li);
					});
				})
				.catch(err => console.log(err));
		}
	}

	const processChange = debounce(searchInput);


</script>
