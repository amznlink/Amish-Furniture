// Function to fetch CSV data and update product display
function fetchDataAndDisplay() {
    fetch('products.csv')
        .then(response => response.text())
        .then(data => {
            const rows = data.split('\n').slice(1); // Remove header row
            const container = document.getElementById('product-container');

            // Create an array to store existing product URLs
            const existingProductUrls = [];

            // Loop through existing product containers and collect their product URLs
            container.querySelectorAll('.product').forEach(productContainer => {
                const productUrl = productContainer.querySelector('img').getAttribute('onclick').match(/'([^']+)'/)[1];
                existingProductUrls.push(productUrl);
            });

            // Loop through new CSV data and update or add product containers
            rows.forEach(row => {
                const columns = row.split(',');
                if (columns.length === 4) { // Check if all columns are present
                    const description = columns[0].trim();
                    const imageUrl = columns[1].trim();
                    const price = columns[2].trim();
                    const productUrl = columns[3].trim();

                    const productHTML = `
                        <div class="product">
                            <img class="product-img" src="${imageUrl}" alt="${description}" onclick="window.open('${productUrl}', '_blank')">
                            <p class="description">${description}</p>
                            <p class="price">Prices Are Subject To Change:<span style="color: red;">$${price}</span></p>
                            <div class="button-container">
                                <!-- Twitter share button for individual product -->
                                <button class="twitter-button" onclick="shareProductToTwitter('${productUrl}')">Post On 𝕏</button>
                                <button class="buy-button" onclick="window.open('${productUrl}', '_blank')">Buy On Amazon.com</button>
                                <!-- Share button -->
                                <button class="share-button" onclick="copyProductUrl('${productUrl}')">Copy And Share</button>
                            </div>
                        </div>
                    `;

                    // If product URL already exists in the DOM, update its HTML; otherwise, append new product container
                    if (existingProductUrls.includes(productUrl)) {
                        const existingProductContainer = container.querySelector(`.product img[onclick="window.open('${productUrl}', '_blank')"]`).parentNode;
                        existingProductContainer.innerHTML = productHTML;
                    } else {
                        container.innerHTML += productHTML;
                    }

                    // Remove the product URL from existingProductUrls array to keep track of updated products
                    const index = existingProductUrls.indexOf(productUrl);
                    if (index > -1) {
                        existingProductUrls.splice(index, 1);
                    }
                }
            });

            // Remove any product containers that correspond to products no longer present in the updated CSV data
            existingProductUrls.forEach(productUrl => {
                const productContainerToRemove = container.querySelector(`.product img[onclick="window.open('${productUrl}', '_blank')"]`).parentNode;
                container.removeChild(productContainerToRemove);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Function to copy product URL to clipboard
function copyProductUrl(productUrl) {
    navigator.clipboard.writeText(productUrl)
        .then(() => {
            console.log('Product URL copied to clipboard:', productUrl);
            alert('Product URL copied to clipboard!');
        })
        .catch(err => {
            console.error('Error copying product URL to clipboard:', err);
            alert('Failed to copy product URL!');
        });
}

// Function to share the entire page URL
function sharePage() {
    const pageUrl = window.location.href;
    const tweetUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}`;
    window.open(tweetUrl, '_blank');
}

// Function to share product URL on Twitter
function shareProductToTwitter(productUrl) {
    const tweetUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(productUrl)}`;
    window.open(tweetUrl, '_blank');
}

// Fetch data initially and then refresh every 60 seconds
fetchDataAndDisplay();
setInterval(fetchDataAndDisplay, 6000000); // 6000000 ms = 10 minutes

// Add event listener to the page share button
document.getElementById('twitter-share-button').addEventListener('click', sharePage);
