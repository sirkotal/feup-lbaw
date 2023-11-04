CREATE SCHEMA IF NOT EXISTS lbaw2345;


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

DROP TYPE IF EXISTS order_status;
DROP TYPE IF EXISTS blocked_status;
DROP TYPE IF EXISTS payment_status;
DROP TYPE IF EXISTS payment_method;

CREATE TYPE order_status AS ENUM ('Shipping', 'Payment Approved', 'Canceled', 'Received');
CREATE TYPE blocked_status AS ENUM ('Blocking', 'Unblocking');
CREATE TYPE payment_status AS ENUM ('Approved', 'Pending', 'Declined');
CREATE TYPE payment_method AS ENUM ('Paypal', 'MBWAY', 'Credit Card', 'Bank Transfer');

CREATE TABLE users (
   id SERIAL PRIMARY KEY,
   dateofbirth DATE,
   username VARCHAR(256) UNIQUE NOT NULL,
   userPath VARCHAR(256), 
   userPassword VARCHAR(256) NOT NULL,
   email VARCHAR(256) UNIQUE NOT NULL,
   isDeleted BOOL DEFAULT false NOT NULL
);

CREATE TABLE orders (
   id SERIAL PRIMARY KEY,
   orderDate DATE NOT NULL,
   itemQuantity INTEGER,
   orderStatus order_status,
   total REAL,
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE paymentTransaction (
   id SERIAL PRIMARY KEY,
   method payment_method,
   paymentStatus payment_status,
   orderId INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE brand (
   id SERIAL PRIMARY KEY,
   brandName VARCHAR(256) UNIQUE NOT NULL
);

CREATE TABLE category (                                  
   id SERIAL PRIMARY KEY,
   categoryName VARCHAR(256) UNIQUE NOT NULL,
   parentCategoryId INTEGER REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE discount (
   id SERIAL PRIMARY KEY,
   startDate DATE NOT NULL,
   endDate DATE NOT NULL,
   percentage REAL NOT NULL
);

CREATE TABLE product (
   id SERIAL PRIMARY KEY,
   productName VARCHAR(256) NOT NULL,
   description TEXT,
   extraInformation TEXT,
   price REAL NOT NULL,
   productPath VARCHAR(256),
   stock INTEGER,
   brandId INTEGER NOT NULL REFERENCES brand (id) ON UPDATE CASCADE ON DELETE CASCADE,
   discountId INTEGER REFERENCES discount (id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE productCategory (
   PRIMARY KEY (productId, categoryId),
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
   categoryId INTEGER NOT NULL REFERENCES category (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE orderedProduct (
   PRIMARY KEY (orderId, productId),
   quantity INTEGER NOT NULL CHECK ( ( quantity > 0  ) ),
   priceBought REAL NOT NULL,
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
   orderId INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE review (
   id SERIAL PRIMARY KEY,
   reviewDate DATE NOT NULL,
   rating REAL,
   title VARCHAR(256) NOT NULL,
   upvoteCount INTEGER,
   reviewText TEXT,
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE notifications (
   id SERIAL PRIMARY KEY,
   notificationDate DATE NOT NULL,
   notificationText VARCHAR(256) NOT NULL,
   isRead BOOL DEFAULT false NOT NULL,
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE changeOfOrder (
   id SERIAL PRIMARY KEY,
   notificationId INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   orderId INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE changeInPrice (
   id SERIAL PRIMARY KEY,
   notificationId INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE itemAvailability (
   id SERIAL PRIMARY KEY,
   notificationId INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE paymentApproved (
   id SERIAL PRIMARY KEY,
   notificationId INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   paymentTransactionId INTEGER NOT NULL REFERENCES paymentTransaction (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE likedReview (
   id SERIAL PRIMARY KEY,
   notificationId INTEGER NOT NULL REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
   reviewId INTEGER NOT NULL REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE report (
   PRIMARY KEY (userId, reviewId),
   reportDate DATE NOT NULL,
   reason VARCHAR(256) NOT NULL,
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   reviewId INTEGER NOT NULL REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE blockAction (                                
   id SERIAL PRIMARY KEY,
   blockDate DATE NOT NULL,
   blockedAction blocked_status NOT NULL,
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE wishlist (
   PRIMARY KEY (userId, productId),
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE shoppingCart (
   PRIMARY KEY (userId, productId),
   quantity INTEGER,
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   productId INTEGER NOT NULL REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE upvote (
   PRIMARY KEY (userId, reviewId),
   userId INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   reviewId INTEGER NOT NULL REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- INDEXES

CREATE INDEX notification_user_id_idx ON "notifications" (userId);

CREATE INDEX product_price_idx ON "product" (price);

CREATE INDEX review_product_id_idx ON "review" (productId);

-- FTS INDEX

ALTER TABLE product
ADD COLUMN product_tsv TSVECTOR;

CREATE OR REPLACE FUNCTION product_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.product_tsv = setweight(to_tsvector('english', NEW.productName), 'A') ||
                          setweight(to_tsvector('english', (SELECT brandName FROM brand WHERE id = NEW.brandId)), 'B') ||
                          setweight(to_tsvector('english', NEW.extraInformation), 'D');
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.productName <> OLD.productName OR NEW.brandId <> OLD.brandId OR NEW.extraInformation <> OLD.extraInformation) THEN
            NEW.product_tsv = setweight(to_tsvector('english', NEW.productName), 'A') ||
                              setweight(to_tsvector('english', (SELECT brandName FROM brand WHERE id = NEW.brandId)), 'B') ||
                              setweight(to_tsvector('english', NEW.extraInformation), 'D');
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
BEGIN
  IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
    UPDATE Product
    SET product_tsv = (
      setweight(to_tsvector('english', P.productName), 'A') ||
      setweight(to_tsvector('english', B.brandName), 'B') ||
      setweight(to_tsvector('english', C.categoryName), 'C') ||
      setweight(to_tsvector('english', P.extraInformation), 'D')
    )
    FROM product P
    JOIN brand B ON P.brandId = B.id
    JOIN productCategory PC ON P.id = PC.productId
    JOIN category C ON PC.categoryId = C.id
    WHERE PC.productId = NEW.productId;
  END IF;

  RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER product_category_search_update
AFTER INSERT OR UPDATE ON productCategory
FOR EACH ROW
EXECUTE PROCEDURE product_category_search_update();

CREATE INDEX product_category_search_idx ON product USING GIN (product_tsv);


-- TRIGGERS

-- TRIGGER 1

CREATE OR REPLACE FUNCTION add_notificationAvailability() RETURNS TRIGGER AS $BODY$
DECLARE notificationId integer;
BEGIN
  IF OLD.stock = 0 AND NEW.stock > 0 THEN
        INSERT INTO notifications (notificationDate, notificationText, userId, isRead) SELECT NOW(), 'There is stock available right now', userId, false FROM Wishlist WHERE Wishlist.productId = NEW.id RETURNING id INTO notificationId;
        INSERT INTO itemAvailability (notificationId, productId) VALUES (notificationId, NEW.id);  
  ELSIF NEW.stock = 1 THEN
        INSERT INTO notifications (notificationDate, notificationText, userId, isRead) SELECT NOW(), 'LAST ITEM AVAILABLE', userId, false FROM Wishlist WHERE Wishlist.productId = NEW.id RETURNING id INTO notificationId;
        INSERT INTO itemAvailability (notificationId, productId) VALUES (notificationId, NEW.id); 
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
DECLARE notificationId integer;
BEGIN
  IF OLD.upvoteCount < NEW.upvoteCount THEN
    INSERT INTO notifications (notificationDate, notificationText, userId, isRead) VALUES (NOW(),'Someone Liked Your Review', NEW.userId, false) RETURNING id INTO notificationId;
    INSERT INTO likedReview (notificationId, reviewId) VALUES (notificationId, NEW.id);  
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationLike
AFTER UPDATE ON review
FOR EACH ROW
EXECUTE PROCEDURE add_notificationLike(); 

update review set upvoteCount = 11 where review.id = 1;


-- TRIGGER 3

CREATE OR REPLACE FUNCTION add_notificationPrice() RETURNS TRIGGER AS $BODY$
DECLARE notificationId integer;
BEGIN
  IF OLD.price < NEW.price THEN
     INSERT INTO notifications (notificationDate, notificationText, userId, isRead) SELECT NOW(), 'The price is higher on ' || NEW.productName, userId, false FROM Wishlist WHERE Wishlist.productId = NEW.id RETURNING id INTO notificationId;
     INSERT INTO changeInPrice (notificationId, productId) VALUES (notificationId, NEW.id);  
  ELSIF OLD.price > NEW.price THEN
	 INSERT INTO notifications (notificationDate, notificationText, userId, isRead) SELECT NOW(), 'The price is lower on ' || NEW.productName, userId, false FROM Wishlist WHERE Wishlist.productId = NEW.id RETURNING id INTO notificationId;
     INSERT INTO changeInPrice (notificationId, productId) VALUES (notificationId, NEW.id);  
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationPrice
AFTER UPDATE ON product
FOR EACH ROW
EXECUTE PROCEDURE add_notificationPrice();

-- TRIGGER 4

CREATE OR REPLACE FUNCTION add_notificationOrder() RETURNS TRIGGER AS $BODY$
DECLARE notificationId integer;
BEGIN
    INSERT INTO notifications (notificationDate, notificationText, userId, isRead) VALUES (NOW(), 'Order Status: ' || NEW.orderStatus, NEW.userId, false) RETURNING id INTO notificationId;
    INSERT INTO changeOfOrder (notificationId, orderId) VALUES (notificationId, NEW.id);   
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER notificationOrder
AFTER INSERT OR UPDATE ON orders
FOR EACH ROW
EXECUTE PROCEDURE add_notificationOrder();

-- TRIGGER 5

CREATE OR REPLACE FUNCTION add_notificationPayment() RETURNS TRIGGER AS $BODY$
DECLARE notificationId integer;
BEGIN
    INSERT INTO notifications (notificationDate, notificationText, userId, isRead) SELECT NOW(), 'Payment Status: ' || NEW.paymentStatus, userId, false FROM orders WHERE orders.id = NEW.orderId RETURNING id INTO notificationId;
    INSERT INTO paymentApproved (notificationId, paymentTransactionId) VALUES (notificationId, NEW.id);  
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
  IF EXISTS (SELECT * FROM review WHERE review.userID = NEW.userID AND review.id = NEW.reviewId) THEN
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
  IF EXISTS (SELECT * FROM review WHERE review.userId = NEW.userId AND review.productId = NEW.productId) THEN
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
  IF (SELECT orderStatus FROM orders WHERE orders.id = NEW.id) = 'Shipping' AND NEW.orderStatus = 'Canceled' THEN
     RAISE EXCEPTION 'This order is already on its way';
  ELSIF (SELECT orderStatus FROM orders WHERE orders.id = NEW.id) = 'Received' AND NEW.orderStatus = 'Canceled' THEN
     RAISE EXCEPTION 'This order is already done';
  ELSIF (SELECT orderStatus FROM orders WHERE orders.id = NEW.id) = 'Canceled' THEN
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

CREATE OR REPLACE FUNCTION delete_notification_read() RETURNS TRIGGER AS $BODY$
BEGIN
   DELETE FROM notifications WHERE notifications.isRead = true;
   RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_notification_read
AFTER UPDATE ON notifications
FOR EACH ROW
EXECUTE PROCEDURE delete_notification_read();

-- TRIGGER 10

CREATE OR REPLACE FUNCTION verify_time() RETURNS TRIGGER AS $BODY$
BEGIN
   IF NEW.reviewDate > Now() THEN
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


-- TRIGGER 11

CREATE OR REPLACE FUNCTION verify_age() RETURNS TRIGGER AS $BODY$
BEGIN
   IF COALESCE((SELECT AGE(NOW(), dateofbirth) FROM users WHERE users.id = NEW.userId), '0 years') < interval '18 years' AND ((SELECT categoryName FROM category WHERE category.id = (SELECT categoryId FROM productCategory WHERE productCategory.productId = NEW.productId)) = 'Tobacco' OR (SELECT categoryName FROM category WHERE category.id = (SELECT categoryId FROM productCategory WHERE productCategory.productId = NEW.productId)) = 'Alcohol') THEN
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
