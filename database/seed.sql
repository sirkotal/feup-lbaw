DROP SCHEMA IF EXISTS lbaw2345 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2345;
SET search_path TO lbaw2345;


DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS product CASCADE;
DROP TABLE IF EXISTS brand CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS discount CASCADE;
DROP TABLE IF EXISTS orderedProduct CASCADE;
DROP TABLE IF EXISTS review CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS changeOfOrder CASCADE;
DROP TABLE IF EXISTS changeInPrice CASCADE;
DROP TABLE IF EXISTS itemAvailability CASCADE;
DROP TABLE IF EXISTS productCategory CASCADE;
DROP TABLE IF EXISTS paymentApproved CASCADE;
DROP TABLE IF EXISTS likedReview CASCADE;
DROP TABLE IF EXISTS paymentTransaction CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS blockAction CASCADE;
DROP TABLE IF EXISTS wishlist CASCADE;
DROP TABLE IF EXISTS shoppingCart CASCADE;
DROP TABLE IF EXISTS upvote CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;

DROP TYPE IF EXISTS order_status;
DROP TYPE IF EXISTS blocked_status;
DROP TYPE IF EXISTS payment_status;
DROP TYPE IF EXISTS payment_method;

CREATE TYPE order_status AS ENUM ('Shipping', 'Payment Approved', 'Waiting for payment', 'Canceled', 'Received');
CREATE TYPE blocked_status AS ENUM ('Blocking', 'Unblocking');
CREATE TYPE payment_status AS ENUM ('Approved', 'Pending', 'Declined');
CREATE TYPE payment_method AS ENUM ('Paypal', 'MBWAY', 'Credit Card', 'Bank Transfer');

CREATE TABLE users (
   id SERIAL PRIMARY KEY,
   date_of_birth DATE,
   username VARCHAR(256) UNIQUE NOT NULL,
   user_path VARCHAR(256), 
   password VARCHAR(256) NOT NULL,
   phone_number VARCHAR(256),
   email VARCHAR(256) UNIQUE NOT NULL,
   is_deleted BOOL DEFAULT false NOT NULL,
   is_admin BOOL DEFAULT false NOT NULL
);

CREATE TABLE orders (
   id SERIAL PRIMARY KEY,
   order_date DATE NOT NULL,
   item_quantity INTEGER,
   order_status order_status,
   total REAL,
   address VARCHAR(255),
   country VARCHAR(50),
   city VARCHAR(50),
   zip_code VARCHAR(20),
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE paymentTransaction (
   id SERIAL PRIMARY KEY,
   method payment_method,
   payment_status payment_status,
   order_id INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE brand (
   id SERIAL PRIMARY KEY,
   brand_name VARCHAR(256) UNIQUE NOT NULL
);

CREATE TABLE category (                                  
   id SERIAL PRIMARY KEY,
   category_name VARCHAR(256) UNIQUE NOT NULL,
   parent_category_id INTEGER REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE discount (
   id SERIAL PRIMARY KEY,
   name VARCHAR NOT NULL,
   start_date DATE NOT NULL,
   end_date DATE NOT NULL,
   percentage REAL NOT NULL
);

CREATE TABLE product (
   id SERIAL PRIMARY KEY,
   product_name VARCHAR(256) NOT NULL,
   description TEXT,
   extra_information TEXT,
   price REAL NOT NULL,
   product_path VARCHAR(256),
   stock INTEGER,
   brand_id INTEGER NOT NULL REFERENCES brand (id) ON UPDATE CASCADE ON DELETE CASCADE,
   discount_id INTEGER REFERENCES discount (id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE productCategory (
   PRIMARY KEY (product_id, category_id),
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
   category_id INTEGER NOT NULL REFERENCES category (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE orderedProduct (
   PRIMARY KEY (order_id, product_id),
   quantity INTEGER NOT NULL CHECK ( ( quantity > 0  ) ),
   price_bought REAL NOT NULL,
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
   order_id INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE review (
   id SERIAL PRIMARY KEY,
   review_date DATE NOT NULL,
   rating REAL,
   title VARCHAR(256) NOT NULL,
   upvote_count INTEGER,
   review_text TEXT,
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE notifications (
   id SERIAL PRIMARY KEY,
   notification_date DATE NOT NULL,
   notification_text VARCHAR(256) NOT NULL,
   is_read BOOL DEFAULT false NOT NULL,
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE changeOfOrder (
   id SERIAL PRIMARY KEY,
   notification_id INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   order_id INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE changeInPrice (
   id SERIAL PRIMARY KEY,
   notification_id INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE itemAvailability (
   id SERIAL PRIMARY KEY,
   notification_id INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE paymentApproved (
   id SERIAL PRIMARY KEY,
   notification_id INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   payment_transaction_id INTEGER NOT NULL REFERENCES paymentTransaction (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE likedReview (
   id SERIAL PRIMARY KEY,
   notification_id INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   review_id INTEGER NOT NULL REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE report (
   PRIMARY KEY (user_id, review_id),
   report_date DATE NOT NULL,
   reason VARCHAR(256) NOT NULL,
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   review_id INTEGER NOT NULL REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE blockAction (                                
   id SERIAL PRIMARY KEY,
   block_date DATE NOT NULL,
   blocked_action blocked_status NOT NULL,
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE wishlist (
   PRIMARY KEY (user_id, product_id),
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE shoppingCart (
   PRIMARY KEY (user_id, product_id),
   quantity INTEGER,
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   product_id INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE upvote (
   PRIMARY KEY (user_id, review_id),
   user_id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   review_id INTEGER NOT NULL REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255),
    created_at TIMESTAMP NULL
);

-- INDEXES

CREATE INDEX notification_user_id_idx ON "notifications" (user_id);

CREATE INDEX product_price_idx ON "product" (price);

CREATE INDEX review_product_id_idx ON "review" (product_id);

-- FTS INDEX

ALTER TABLE product
ADD COLUMN product_tsv TSVECTOR;

CREATE OR REPLACE FUNCTION product_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.product_tsv = setweight(to_tsvector('english', NEW.product_name), 'A') ||
                          setweight(to_tsvector('english', (SELECT brand_name FROM brand WHERE id = NEW.brand_id)), 'B') ||
                          setweight(to_tsvector('english', NEW.extra_information), 'D');
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.product_name <> OLD.product_name OR NEW.brand_id <> OLD.brand_id OR NEW.extra_information <> OLD.extra_information) THEN
            NEW.product_tsv = setweight(to_tsvector('english', NEW.product_name), 'A') ||
                              setweight(to_tsvector('english', (SELECT brand_name FROM brand WHERE id = NEW.brand_id)), 'B') ||
                              setweight(to_tsvector('english', NEW.extra_information), 'D');
        END IF;
    END IF;

    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER product_search_update
BEFORE INSERT OR UPDATE ON product
FOR EACH ROW
EXECUTE PROCEDURE product_search_update();

CREATE INDEX product_search_idx ON product USING GIN (product_tsv);


-- FTS 2 : for categories


CREATE OR REPLACE FUNCTION product_category_search_update() RETURNS TRIGGER AS $$
DECLARE
  product_name_text TEXT;
  brand_name_text TEXT;
  category_name_text TEXT;
  extra_info_text TEXT;
BEGIN
  IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
    SELECT
      COALESCE(product.product_name || ' ', ''),
      COALESCE(B.brand_name || ' ', ''),
      COALESCE(C.category_name || ' ', ''),
      COALESCE(product.extra_information || ' ', '')
    INTO
      product_name_text,
      brand_name_text,
      category_name_text,
      extra_info_text
    FROM product
    JOIN brand AS B ON product.brand_id = B.id
    JOIN productCategory AS PC ON product.id = PC.product_id
    JOIN category AS C ON PC.category_id = C.id
    WHERE product.id = NEW.product_id;

    UPDATE Product AS P
    SET product_tsv = (
      setweight(to_tsvector('english', product_name_text), 'A') ||
      setweight(to_tsvector('english', brand_name_text), 'B') ||
      setweight(to_tsvector('english', category_name_text), 'C') ||
      setweight(to_tsvector('english', extra_info_text), 'D')
    )
    WHERE P.id = NEW.product_id;
  END IF;

  RETURN NEW;
END $$ LANGUAGE plpgsql;



CREATE TRIGGER product_category_search_update
AFTER INSERT OR UPDATE ON productCategory
FOR EACH ROW
EXECUTE PROCEDURE product_category_search_update();

CREATE INDEX product_category_search_idx ON product USING GIN (product_tsv);


-- TRIGGERS

-- TRIGGER 1

CREATE OR REPLACE FUNCTION add_notificationAvailability() RETURNS TRIGGER AS $BODY$
DECLARE notification_id integer;
BEGIN
  IF OLD.stock = 0 AND NEW.stock > 0 THEN
        INSERT INTO notifications (notification_date, notification_text, user_id, is_read) SELECT NOW(), 'There is stock available right now', user_id, false FROM Wishlist WHERE Wishlist.product_id = NEW.id;
        INSERT INTO itemAvailability (notification_id, product_id) SELECT id, NEW.id FROM notifications WHERE NOT EXISTS (SELECT 1 FROM changeInPrice AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM changeOfOrder AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM itemAvailability AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM paymentApproved AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM likedReview AS c WHERE c.notification_id = notifications.id);  
  ELSIF NEW.stock = 1 THEN
        INSERT INTO notifications (notification_date, notification_text, user_id, is_read) SELECT NOW(), 'LAST ITEM AVAILABLE', user_id, false FROM Wishlist WHERE Wishlist.product_id = NEW.id;
        INSERT INTO itemAvailability (notification_id, product_id) SELECT id, NEW.id FROM notifications WHERE NOT EXISTS (SELECT 1 FROM changeInPrice AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM changeOfOrder AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM itemAvailability AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM paymentApproved AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM likedReview AS c WHERE c.notification_id = notifications.id); 
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationAvailability
AFTER UPDATE ON product
FOR EACH ROW
EXECUTE PROCEDURE add_notificationAvailability();

-- TRIGGER 2

CREATE OR REPLACE FUNCTION add_notificationLike() RETURNS TRIGGER AS $BODY$
DECLARE notification_id integer;
BEGIN
    INSERT INTO notifications (notification_date, notification_text, user_id, is_read) VALUES (NOW(),'Someone Liked Your Review', (SELECT user_id FROM review WHERE review.id = NEW.review_id), false) RETURNING id INTO notification_id;
    INSERT INTO likedReview (notification_id, review_id) VALUES (notification_id, NEW.review_id);  
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationLike
AFTER INSERT ON upvote
FOR EACH ROW
EXECUTE PROCEDURE add_notificationLike(); 



-- TRIGGER 3

CREATE OR REPLACE FUNCTION add_notificationPrice() RETURNS TRIGGER AS $BODY$
DECLARE notification_id integer;
BEGIN
  IF (NEW.discount_id IS NOT NULL AND OLD.discount_id IS NULL) THEN
     INSERT INTO notifications (notification_date, notification_text, user_id, is_read) SELECT NOW(), NEW.product_name || ' is now on discount!', user_id, false FROM Wishlist WHERE Wishlist.product_id = NEW.id;
     INSERT INTO changeInPrice (notification_id, product_id) SELECT id, NEW.id FROM notifications WHERE NOT EXISTS (SELECT 1 FROM changeInPrice AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM changeOfOrder AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM itemAvailability AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM paymentApproved AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM likedReview AS c WHERE c.notification_id = notifications.id);
  ELSIF (NEW.discount_id IS NOT NULL AND OLD.discount_id IS NOT NULL AND OLD.discount_id != NEW.discount_id) THEN
     INSERT INTO notifications (notification_date, notification_text, user_id, is_read) SELECT NOW(), 'New discount on ' || NEW.product_name, user_id, false FROM Wishlist WHERE Wishlist.product_id = NEW.id;
     INSERT INTO changeInPrice (notification_id, product_id) SELECT id, NEW.id FROM notifications WHERE NOT EXISTS (SELECT 1 FROM changeInPrice AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM changeOfOrder AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM itemAvailability AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM paymentApproved AS c WHERE c.notification_id = notifications.id) AND NOT EXISTS (SELECT 1 FROM likedReview AS c WHERE c.notification_id = notifications.id);
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

-- TRIGGER 3^-1

CREATE TRIGGER notificationPrice
AFTER UPDATE ON product
FOR EACH ROW
EXECUTE PROCEDURE add_notificationPrice();

CREATE OR REPLACE FUNCTION delete_notificationsAndChangeInPriceOnDiscountRemoval() RETURNS TRIGGER AS $BODY$
DECLARE
  notification_id_to_delete INTEGER;
BEGIN
  IF (OLD.discount_id IS NOT NULL AND NEW.discount_id IS NULL) THEN
    SELECT notification_id INTO notification_id_to_delete FROM changeInPrice WHERE product_id = OLD.id;
    DELETE FROM notifications WHERE id = notification_id_to_delete;
    DELETE FROM changeInPrice WHERE product_id = OLD.id;
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER deleteNotificationsAndChangeInPriceOnDiscountRemoval
AFTER UPDATE ON product
FOR EACH ROW
WHEN (OLD.discount_id IS NOT NULL AND NEW.discount_id IS NULL)
EXECUTE PROCEDURE delete_notificationsAndChangeInPriceOnDiscountRemoval();

-- TRIGGER 4

CREATE OR REPLACE FUNCTION add_notificationOrder() RETURNS TRIGGER AS $BODY$
DECLARE notification_id integer;
BEGIN
    INSERT INTO notifications (notification_date, notification_text, user_id, is_read) VALUES (NOW(), NEW.order_status, NEW.user_id, false) RETURNING id INTO notification_id;
    INSERT INTO changeOfOrder (notification_id, order_id) VALUES (notification_id, NEW.id);   
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationOrder
AFTER UPDATE ON orders
FOR EACH ROW
EXECUTE PROCEDURE add_notificationOrder();

-- TRIGGER 5

CREATE OR REPLACE FUNCTION add_notificationPayment() RETURNS TRIGGER AS $BODY$
DECLARE notification_id integer;
BEGIN
    INSERT INTO notifications (notification_date, notification_text, user_id, is_read) SELECT NOW(), 'Payment Status: ' || NEW.payment_status, user_id, false FROM orders WHERE orders.id = NEW.order_id RETURNING id INTO notification_id;
    INSERT INTO paymentApproved (notification_id, payment_transaction_id) VALUES (notification_id, NEW.id);  
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationPayment
AFTER INSERT OR UPDATE ON paymentTransaction
FOR EACH ROW
EXECUTE PROCEDURE add_notificationPayment();

-- TRIGGER 6

CREATE OR REPLACE FUNCTION verify_vote() RETURNS TRIGGER AS $BODY$
BEGIN
  IF EXISTS (SELECT * FROM review WHERE review.user_id = NEW.user_id AND review.id = NEW.review_id) THEN
     RAISE EXCEPTION 'A user cant vote in his own review';
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER verify_vote
BEFORE INSERT ON upvote
FOR EACH ROW
EXECUTE PROCEDURE verify_vote();


-- TRIGGER 7

CREATE OR REPLACE FUNCTION verify_review() RETURNS TRIGGER AS $BODY$
BEGIN
  IF EXISTS (SELECT * FROM review WHERE review.user_id = NEW.user_id AND review.product_id = NEW.product_id) THEN
     RAISE EXCEPTION 'This user already reviewed this product';
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER verify_review
BEFORE INSERT ON review
FOR EACH ROW
EXECUTE PROCEDURE verify_review();

-- TRIGGER 8

CREATE OR REPLACE FUNCTION verify_order_delete() RETURNS TRIGGER AS $BODY$
BEGIN
  IF (SELECT order_status FROM orders WHERE orders.id = NEW.id) = 'Shipping' AND NEW.order_status = 'Canceled' THEN
     RAISE EXCEPTION 'This order is already on its way';
  ELSIF (SELECT order_status FROM orders WHERE orders.id = NEW.id) = 'Received' AND NEW.order_status = 'Canceled' THEN
     RAISE EXCEPTION 'This order is already done';
  ELSIF (SELECT order_status FROM orders WHERE orders.id = NEW.id) = 'Canceled' THEN
     RAISE EXCEPTION 'This order is already canceled';
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER verify_order_delete
BEFORE UPDATE ON orders
FOR EACH ROW
EXECUTE PROCEDURE verify_order_delete();

-- TRIGGER 9

CREATE OR REPLACE FUNCTION verify_time() RETURNS TRIGGER AS $BODY$
BEGIN
   IF NEW.review_date > Now() THEN
     RAISE EXCEPTION 'Problems with date time';
   END IF;
   RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_time
BEFORE INSERT ON review
FOR EACH ROW
EXECUTE PROCEDURE verify_time();


-- TRIGGER 10

CREATE OR REPLACE FUNCTION verify_age() RETURNS TRIGGER AS $BODY$
BEGIN
   IF COALESCE((SELECT AGE(NOW(), date_of_birth) FROM users WHERE users.id = NEW.user_id), '0 years') < interval '18 years' AND ((SELECT category_name FROM category WHERE category.id = (SELECT category_id FROM productCategory WHERE productCategory.product_id = NEW.product_id)) = 'Tobacco' OR (SELECT category_name FROM category WHERE category.id = (SELECT category_id FROM productCategory WHERE productCategory.product_id = NEW.product_id)) = 'Alcohol') THEN
     RAISE EXCEPTION 'You need to be over 18';
   END IF;
   RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_age
BEFORE INSERT ON shoppingCart
FOR EACH ROW
EXECUTE PROCEDURE verify_age();


-- POPULATE

INSERT INTO users (username, user_path, date_of_birth, password, email, phone_number, is_admin) VALUES
   ('sergio', 'def', '1990-2-14', '$2y$10$2WzRs0g68ZQnote4JIz2SOge7me5V88u22SqKCcslGJNwlpncyXUC', 'lbaw2345@gmail.com', 918238492, true);
   
INSERT INTO users (username, user_path, date_of_birth, password, email, phone_number) VALUES
   ('saul_goodman', 'def', '1999-10-08', '$2y$10$2WzRs0g68ZQnote4JIz2SOge7me5V88u22SqKCcslGJNwlpncyXUC', 'thegoodmansaul@gmail.com' ,918223849),
   ('pescator', 'def', '1999-10-08', '$2y$10$2WzRs0g68ZQnote4JIz2SOge7me5V88u22SqKCcslGJNwlpncyXUC', 'mlgpescator@gmail.com', 918239029),
   ('impostor', 'def', '1978-07-27', 'venting', 'sussyamongus@outlook.com', 915623849),
   ('mr-white', 'def', '2003-05-10', 'albuquerque', 'walterwhite@outlook.com', 918258619);

INSERT INTO brand (brand_name) VALUES
   ('Pepsi'),
   ('Nestle'),
   ('Kellogg''s'),
   ('General Mills'),
   ('Heinz'),
   ('Kraft'),
   ('Unilever'),
   ('Danone'),
   ('Campbell''s'),
   ('Hershey''s'),
   ('Procter & Gamble'),
   ('Johnson & Johnson'),
   ('Mars'),
   ('Mondelez International'),
   ('Conagra Brands'),
   ('Cargill'),
   ('Archer Daniels Midland'),
   ('Coca-Cola Enterprises'),
   ('Colgate-Palmolive'),
   ('Tyson Foods');

INSERT INTO category (category_name, parent_category_id) VALUES
   ('Fresh Produce', NULL),
   ('Beverages', NULL),
   ('Dairy & Eggs', NULL),
   ('Bakery', NULL),
   ('Meat & Seafood', NULL),
   ('Pantry Staples', NULL),
   ('Snacks', NULL),
   ('Frozen Foods', NULL),
   ('Health & Beauty', NULL),
   ('Household', NULL),
   ('Baby & Kids', NULL),
   ('Pet Supplies', NULL),
   ('Organic', NULL),
   ('Vegetables', 1),
   ('Fruits', 1),
   ('Juices', 2),
   ('Sodas', 2),
   ('Milk & Cream', 3),
   ('Cheese', 3),
   ('Bread', 4),
   ('Cakes & Pastries', 4),
   ('Beef', 5),
   ('Chicken', 5),
   ('Canned Goods', 6),
   ('Grains & Pasta', 6),
   ('Chips & Crisps', 7),
   ('Chocolate & Candy', 7),
   ('Frozen Meals', 8),
   ('Ice Cream', 8),
   ('Skincare', 9),
   ('Hair Care', 9),
   ('Cleaning Supplies', 10),
   ('Laundry', 10),
   ('Baby Food', 11),
   ('Toys', 11),
   ('Pet Food', 12),
   ('Pet Toys', 12),
   ('Organic Vegetables', 13),
   ('Organic Fruits', 13);

INSERT INTO discount (name, start_date, end_date, percentage) VALUES
   ('Christmas', '2023-01-01', '2023-12-20', 10),
   ('Halloween', '2023-01-01', '2023-12-19', 20);

INSERT INTO product (product_name, description, extra_information, price, product_path, stock, brand_id, discount_id) VALUES
   ('Bananas', 'Fresh and ripe bananas', 'Origin: Ecuador', 1.29, '/images/products/bananas.png', 200, 1, 1),
   ('Greek Yogurt', 'Creamy Greek yogurt', 'Flavor: Strawberry', 2.49, '/products/greek-yogurt', 120, 2, NULL),
   ('Apples', 'Crisp and juicy apples', 'Variety: Granny Smith', 1.99, '/images/products/apples.png', 180, 1, NULL),
   ('Orange Juice', 'Freshly squeezed orange juice', 'No added sugar', 3.49, '/products/orange-juice', 90, 2, 2),
   ('Cheddar Cheese', 'Sharp cheddar cheese block', 'Aged: 12 months', 4.79, '/images/products/cheddar-cheese.png', 75, 3, NULL),
   ('Whole Wheat Bread', 'Nutritious whole wheat bread', 'Sliced', 2.99, '/products/whole-wheat-bread', 100, 4, NULL),
   ('Ground Beef', 'Lean ground beef', 'Grass-fed', 5.49, '/images/products/ground-beef.png', 50, 5, NULL),
   ('Canned Tomatoes', 'Diced tomatoes in can', 'Organic', 1.79, '/products/canned-tomatoes', 120, 6, NULL),
   ('Potato Chips', 'Crunchy potato chips', 'Flavor: BBQ', 2.29, '/images/products/potato-chips.png', 150, 7, NULL),
   ('Dark Chocolate Bar', 'Rich dark chocolate', 'Cocoa Percentage: 70%', 3.99, '/products/dark-chocolate', 80, 9, NULL),
   ('Frozen Pizza', 'Classic frozen pizza', 'Toppings: Pepperoni', 6.99, '/images/products/frozen-pizza.png', 40, 8, NULL),
   ('Shampoo', 'Gentle cleansing shampoo', 'For all hair types', 5.49, '/products/shampoo', 110, 10, NULL),
   ('Dish Soap', 'Effective dishwashing soap', 'Lemon Scented', 2.99, '/images/products/dish-soap.png', 200, 10, NULL),
   ('Baby Cereal', 'Nutritious baby cereal', 'Stage: 1', 3.29, '/products/baby-cereal', 80, 11, NULL),
   ('Cat Food', 'Premium cat food', 'Flavor: Salmon', 4.49, '/images/products/cat-food.png', 60, 12, NULL),
   ('Organic Carrots', 'Fresh organic carrots', 'Locally sourced', 2.49, '/products/organic-carrots', 150, 13, NULL),
   ('Strawberries', 'Juicy and sweet strawberries', 'Freshly picked', 2.99, '/images/products/strawberries.png', 120, 1, NULL),
   ('Sparkling Water', 'Refreshing sparkling water', 'Lemon Flavor', 1.49, '/products/sparkling-water', 180, 2, NULL),
   ('Mozzarella Cheese', 'Creamy mozzarella cheese', 'Fresh', 3.99, '/images/products/mozzarella-cheese.png', 90, 3, NULL),
   ('Bagels', 'Soft and chewy bagels', 'Variety: Plain', 2.29, '/products/bagels', 150, 4, NULL),
   ('Salmon Fillet', 'Fresh salmon fillet', 'Wild-caught', 9.99, '/images/products/salmon-fillet.png', 40, 5, NULL),
   ('Pasta Sauce', 'Tomato pasta sauce', 'Garlic & Herb', 2.79, '/products/pasta-sauce', 120, 6, NULL),
   ('Tortilla Chips', 'Crunchy tortilla chips', 'Gluten-free', 2.79, '/images/products/tortilla-chips.png', 100, 7, NULL),
   ('Milk Chocolate Bar', 'Smooth milk chocolate', 'Family size', 2.49, '/products/milk-chocolate', 120, 9, NULL),
   ('Frozen Vegetables Mix', 'Assorted frozen vegetables', 'Peas, carrots, corn', 2.99, '/images/products/frozen-vegetables.png', 80, 8, NULL),
   ('Conditioner', 'Nourishing hair conditioner', 'Argan Oil', 6.49, '/products/conditioner', 90, 10, NULL),
   ('All-Purpose Cleaner', 'Versatile cleaning solution', 'Lavender Scent', 3.49, '/images/products/all-purpose-cleaner.png', 160, 10, NULL),
   ('Baby Wipes', 'Gentle baby wipes', 'Sensitive Skin', 4.29, '/products/baby-wipes', 70, 11, NULL),
   ('Dog Food', 'Nutritious dog food', 'Grain-free', 7.99, '/images/products/dog-food.png', 100, 12, NULL),
   ('Organic Spinach', 'Fresh organic spinach', 'Locally grown', 3.49, '/products/organic-spinach', 130, 13, NULL),
   ('Avocados', 'Ripe avocados', 'Variety: Hass', 1.99, '/images/products/avocados.png', 150, 1, NULL),
   ('Green Tea', 'Premium green tea', 'Organic', 3.49, '/products/green-tea', 100, 2, NULL),
   ('Swiss Cheese', 'Mild Swiss cheese', 'Sliced', 4.29, '/images/products/swiss-cheese.png', 80, 3, NULL),
   ('Croissants', 'Flaky butter croissants', 'Freshly baked', 2.79, '/products/croissants', 120, 4, NULL),
   ('Shrimp', 'Fresh shrimp', 'Peeled and deveined', 12.99, '/images/products/shrimp.png', 60, 5, NULL),
   ('Rice', 'Long-grain white rice', 'Non-GMO', 5.49, '/products/rice', 90, 6, NULL),
   ('Popcorn', 'Classic popcorn kernels', 'Butter Flavor', 1.99, '/images/products/popcorn.png', 150, 7, NULL),
   ('White Chocolate Bar', 'Sweet white chocolate', 'Creamy texture', 3.99, '/products/white-chocolate', 80, 9, NULL),
   ('Frozen Fruits Mix', 'Assorted frozen fruits', 'Strawberries, blueberries, mango', 6.99, '/images/products/frozen-fruits.png', 70, 8, NULL),
   ('Leave-In Conditioner', 'Moisturizing leave-in conditioner', 'Coconut Oil', 7.49, '/products/leave-in-conditioner', 70, 10, NULL),
   ('Glass Cleaner', 'Streak-free glass cleaner', 'Ammonia-Free', 4.99, '/images/products/glass-cleaner.png', 120, 10, NULL),
   ('Baby Lotion', 'Gentle baby lotion', 'Calendula Extract', 5.29, '/products/baby-lotion', 90, 11, NULL),
   ('Cat Litter', 'Clumping cat litter', 'Odor control', 8.99, '/images/products/cat-litter.png', 80, 12, NULL),
   ('Organic Tomatoes', 'Fresh organic tomatoes', 'Vine-ripened', 2.79, '/products/organic-tomatoes', 100, 13, NULL);

INSERT INTO productCategory (product_id, category_id) VALUES
   (1, 15), 
   (2, 18), 
   (3, 15), 
   (4, 16), 
   (5, 19), 
   (6, 20), 
   (7, 22), 
   (8, 24), 
   (9, 26), 
   (10, 30), 
   (11, 28), 
   (12, 31), 
   (13, 32), 
   (14, 34), 
   (15, 36), 
   (16, 38), 
   (17, 15), 
   (18, 16), 
   (19, 19), 
   (20, 20), 
   (21, 22), 
   (22, 24), 
   (23, 26), 
   (24, 30), 
   (25, 28), 
   (26, 31), 
   (27, 32), 
   (28, 34), 
   (29, 36), 
   (30, 38), 
   (31, 15), 
   (32, 16), 
   (33, 19), 
   (34, 20), 
   (35, 22), 
   (36, 24), 
   (37, 26), 
   (38, 30), 
   (39, 28),
   (40, 31), 
   (41, 32), 
   (42, 34), 
   (43, 36), 
   (44, 38); 

INSERT INTO orders (order_date, item_quantity, order_status, total, user_id, address, country, city, zip_code) VALUES
   ('2023-01-15', 3, 'Shipping', 150.0, 2, '123 Main St', 'USA', 'New York', '10001'),
   ('2023-01-20', 2, 'Payment Approved', 150.0, 2, '456 Oak St', 'Canada', 'Toronto', '13133');


INSERT INTO paymentTransaction (method, payment_status, order_id) VALUES
   ('Credit Card', 'Approved', 1),
   ('Paypal', 'Approved', 2);

INSERT INTO orderedProduct (quantity, price_bought, product_id, order_id) VALUES
   (3, 150.0, 1, 1),
   (3, 150.0, 2, 1),
   (2, 150.0, 2, 2);

INSERT INTO review (review_date, rating, title, upvote_count, review_text, user_id, product_id) VALUES
   ('2023-02-01', 4.5, 'Great product', 10, 'This is a great product.', 3, 1),
   ('2023-02-05', 4.0, 'Good product', 5, 'This is a good product.', 3, 2);

INSERT INTO report (report_date, reason, user_id, review_id) VALUES
   ('2023-03-05', 'Spam', 2, 1),
   ('2023-03-01', 'Inappropriate content', 1, 1);

INSERT INTO blockAction (block_date, blocked_action, user_id) VALUES
   ('2023-03-15', 'Blocking', 2),
   ('2023-03-15', 'Unblocking', 2);

INSERT INTO upvote (user_id, review_id) VALUES
   (1, 2),
   (2, 1);
