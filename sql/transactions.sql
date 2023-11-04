--- TRAN01 --- Adding product to shopping cart 

DO $$ 
DECLARE
    stock_available integer;
BEGIN
    SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
    SELECT stock INTO stock_available
    FROM product
    WHERE id = $product_id
    FOR UPDATE;

    IF stock_available >= 1 THEN
        INSERT INTO shoppingCart (userId, productId, quantity)
        VALUES ($user_id, $product_id, 1);

        UPDATE product
        SET stock = stock_available - 1
        WHERE id = $product_id;

        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
	
END $$;

--- TRAN02 --- Deleting/changing to anonymous customer information after account deleted

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; 

UPDATE users
SET username = 'none',
    userPassword = 'none',
    userPath = 'img/default.png',
    email = $user_id,
    isDeleted = true
WHERE id = $user_id;

DELETE FROM notifications
WHERE notifications.userId = $user_id;

COMMIT;
END TRANSACTION;

--- TRAN03 --- Moving shopping cart items to order

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

BEGIN;
DO $$

DECLARE torder INTEGER;
	BEGIN
	INSERT INTO orders(orderDate,userId)
	VALUES (now(), $user_id)
	RETURNING id INTO torder;

	INSERT INTO orderedProduct(orderId, productId, quantity, priceBought)
	SELECT torder, shoppingCart.productId, shoppingCart.quantity, product.price
	FROM shoppingCart
	INNER JOIN product ON shoppingCart.productId = product.id
	WHERE shoppingCart.userId = $user_id;

	UPDATE orders 
	SET itemQuantity = (SELECT SUM(orderedProduct.quantity) FROM orderedProduct WHERE orderedProduct.orderId = id),
		total = (SELECT SUM(orderedProduct.quantity*orderedProduct.priceBought) FROM orderedProduct WHERE orderedProduct.orderId = id)
	WHERE userId = $user_id;

	DELETE FROM shoppingCart
	WHERE shoppingCart.userId = $user_id;	
END $$;
COMMIT;


--- TRAN04 --- Displaying the first 20 items froma specific category

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;

SELECT product.productName, category.categoryName
FROM product
INNER JOIN productCategory ON productCategory.productId = product.id 
INNER JOIN category ON category.id = productCategory.categoryId
LIMIT 20;

COMMIT;

END TRANSACTION;

---





