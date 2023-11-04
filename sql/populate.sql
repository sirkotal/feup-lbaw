-- Insert sample data into the users table
INSERT INTO users (username, userPath, dateofbirth, userPassword, email) VALUES
   ('saul_goodman', '/users/saul_goodman', '1999-10-08', 'bettercallsaul', 'thegoodmansaul@gmail.com'),
   ('pescator', '/users/pescator', '1999-10-08', '1000hrsofCSGO', 'mlgpescator@gmail.com'),
   ('impostor', '/users/impostor', '1978-07-27', 'venting', 'sussyamongus@outlook.com'),
   ('mr-white', '/users/mr-white', '2003-05-10', 'albuquerque', 'walterwhite@outlook.com');

-- Insert sample data into the brand table
INSERT INTO brand (brandName) VALUES
   ('Brand A'),
   ('Brand B'),
   ('Brand C');

-- Insert sample data into the category table
INSERT INTO category (categoryName, parentCategoryId) VALUES
   ('Category A', NULL),
   ('Category B', NULL),
   ('Subcategory A1', 1);

-- Insert sample data into the discount table
INSERT INTO discount (startDate, endDate, percentage) VALUES
   ('2023-01-01', '2023-12-31', 10),
   ('2023-01-01', '2023-12-31', 20);

-- Insert sample data into the product table
INSERT INTO product (productName, description, extraInformation, price, productPath, stock, brandId, discountId) VALUES
   ('Product 1', 'Description for Product 1', 'Info for Product 1', 50.0, '/products/product-1', 100, 1, 1),
   ('Product 2', 'Description for Product 2', 'Info for Product 2', 75.0, '/products/product-2', 200, 2, 2);

-- Insert sample data into the productCategory table
INSERT INTO productCategory (productId, categoryId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the orders table
INSERT INTO orders (orderDate, itemQuantity, orderStatus, total, userId) VALUES
   ('2023-01-15', 3, 'Shipping', 150.0, 1),
   ('2023-01-20', 2, 'Payment Approved', 150.0, 2);

INSERT INTO paymentTransaction (method, paymentStatus, orderId) VALUES
   ('Credit Card', 'Approved', 1),
   ('Paypal', 'Approved', 2);

-- Insert sample data into the orderedProduct table
INSERT INTO orderedProduct (quantity, priceBought, productId, orderId) VALUES
   (3, 150.0, 1, 1),
   (2, 150.0, 2, 2);

-- Insert sample data into the review table
INSERT INTO review (reviewDate, rating, title, upvoteCount, reviewText, userId, productId) VALUES
   ('2023-02-01', 4.5, 'Great product', 10, 'This is a great product.', 1, 1),
   ('2023-02-05', 4.0, 'Good product', 5, 'This is a good product.', 2, 2);

-- Insert sample data into the notifications table
INSERT INTO notifications (notificationDate, notificationText, isRead, userId) VALUES
   ('2023-02-10', 'New notification 1', false, 1),
   ('2023-02-15', 'New notification 2', true, 2);

-- Insert sample data into the changeOfOrder table
INSERT INTO changeOfOrder (notificationId, orderId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the changeInPrice table
INSERT INTO changeInPrice (notificationId, productId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the itemAvailability table
INSERT INTO itemAvailability (notificationId, productId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the paymentApproved table
INSERT INTO paymentApproved (notificationId, paymentTransactionId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the likedReview table
INSERT INTO likedReview (notificationId, reviewId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the report table
INSERT INTO report (reportDate, reason, userId, reviewId) VALUES
   ('2023-03-01', 'Inappropriate content', 1, 1),
   ('2023-03-05', 'Spam', 2, 2);

-- Insert sample data into the blockAction table
INSERT INTO blockAction (blockDate, blockedAction, userId) VALUES
   ('2023-03-10', 'Blocking', 1),
   ('2023-03-15', 'Unblocking', 2);

-- Insert sample data into the wishlist table
INSERT INTO wishlist (userId, productId) VALUES
   (1, 1),
   (2, 2);

-- Insert sample data into the shoppingCart table
INSERT INTO shoppingCart (userId, productId, quantity) VALUES
   (1, 2, 3),
   (2, 1, 4);

-- Insert sample data into the upvote table
INSERT INTO upvote (userId, reviewId) VALUES
   (1, 2),
   (2, 1);