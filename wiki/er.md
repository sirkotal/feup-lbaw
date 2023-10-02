# Project Vision

## A1: Cappuccino

Developed by FEUP students, Cappuccino is a project aimed at developing an online grocery shop, marketed for users that wish to do their grocery shopping online in a more convenient, user-friendly way.
 
Our primary objective is to develop a web application for an online shop that replicates the experience of visiting a supermarket, allowing its users to easily browse and buy a diverse range of products from their own home. The ease of online shopping and the necessity of buying groceries come together to give our customers the ability to get what they need without the need of commuting to a physical supermarket.

The website's intuitive design enhances the experience for our visitors, allowing them to effortlessly search and filter products of their choice. Products will be neatly categorized, making it easy for users to find what they're looking for. Upon registration, customers can add desired items to their shopping cart or save them to their wish list for future reference. To maintain transparency and trust in our products and services, customers can also provide reviews and comments on any item, ensuring that their valuable feedback is shared with our community. The review author will be able to change or remove the review to make sure it reflects his opinion over time.

Our notification system plays a crucial role in keeping customers informed about various aspects of their shopping experience. Whether it's updates on order status, product availability, or special promotions, our notification system will provide timely and relevant information to enhance the overall shopping experience. We are committed to delivering a convenient and enjoyable online grocery shopping experience for our valued customers.
To ensure that the website runs smoothly, a team of administrators will be defined before the website launch. These administrators are responsible for tasks such as product inventory management and user account administration. Their presence ensures that the platform remains well-maintained, up-to-date and responsive to the evolving needs of our users.

Our main goal is user satisfaction. We are committed to providing a user-friendly platform that blends simplicity with extensive functionalities. Our website is designed to deliver a simple and intuitive user experience, privileging a responsive design which allows for a smooth experience across various devices. This dedication to accessibility ensures that users can conveniently access our online grocery shop wherever and however they please.


## A2: Actors and User Stories

This artifact defines the actors and respective user stories to better comprehend the project’s requirements and interactions between the different users.

### 1\. Actors

For the online shop Cappuccino, the actors are represented in the figure below:

![lbaw_actors_v2](uploads/9b616068a2e23b1ecbdaa1bb5a5a3c33/lbaw_actors_v2.png "Figure 1: Actors.")

*Figure 1: Actors.*

| Identifier | Description |
|------------|-------------|
| User | Generic user that has access to public information, like the products for sale. |
| Customer | A registered user of the website who can browse, shop for groceries, and manage their account. |
| Visitor | An unauthenticated user who can explore the website and has the option to register or sign in. |
| Review Author | An authenticated user who has provided a review of a product. |
| Administrator | An authenticated user responsible for managing users, products, and overseeing site functions. |
| OAuth API | External OAuth API that can be used to register or authenticate into the system. |

*Table 1: Actors.*

### 2\. User Stories

User stories organized by actor.

#### 2.1 User:

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Priority**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>US01</td>
<td>Search Items</td>
<td>High</td>
<td>As a User, I want to search different items to find what I’m looking for.</td>
</tr>
<tr>
<td>US02</td>
<td>Exact Match Search</td>
<td>High</td>
<td>As a User, I want to perform an exact match search so that I can quickly find the specific items I'm looking for.</td>
</tr>
<tr>
<td>US03</td>
<td>Full-text Search</td>
<td>High</td>
<td>As a User, I want to perform a full-text search so that I can discover relevant items even if I have partial or related keywords.</td>
</tr>
<tr>
<td>US04</td>
<td>View Home</td>
<td>High</td>
<td>As a User, I want to access the home page, so that I can see a brief presentation of the website</td>
</tr>
<tr>
<td>US05</td>
<td>View FAQ</td>
<td>High</td>
<td>As a User, I want to access the FAQ page so that I can get quick answers to questions I may have.</td>
</tr>
<tr>
<td>US06</td>
<td>View About</td>
<td>High</td>
<td>As a User I want to access the about page, so that I can get extra information about the website.</td>
</tr>
<tr>
<td>US07</td>
<td>View Contacts</td>
<td>High</td>
<td>As a User I want to access the contacts, so that I can contact those responsible for the platform.</td>
</tr>
<tr>
<td>US08</td>
<td>View Product Page</td>
<td>High</td>
<td>As a User, I want to access the product page, so that I can access extra information about the product.</td>
</tr>
<tr>
<td>US09</td>
<td>Order Search</td>
<td>High</td>
<td>As a User, I want to order my search so that I can manage my priorities better.</td>
</tr>
<tr>
<td>US010</td>
<td>Add to Cart</td>
<td>High</td>
<td>As a User, I want to add products to my shopping carts so that I can incrementally add what I want to buy.</td>
</tr>
<tr>
<td>US011</td>
<td>Search Filters</td>
<td>Medium</td>
<td>As a User, I want to use filters so I can find the product I’m looking for easier and faster.</td>
</tr>
<tr>
<td>US012</td>
<td>Search over multiple attributes</td>
<td>Medium</td>
<td>As a User, I want the ability to perform searches that consider multiple attributes, such as product name, category, and price range so that I can easily have a selection of items that are of my interest.</td>
</tr>
<tr>
<td>US013</td>
<td>Manage Shopping Cart</td>
<td>Medium</td>
<td>As a User, I want to view and update the contents of the shopping cart so that I can manage the products I want with ease.</td>
</tr>
<tr>
<td>US014</td>
<td>Browse Product Categories</td>
<td>Medium</td>
<td>As a User, I want to select specific product categories, so that I can browse any items on the category I'm interested in.</td>
</tr>
<tr>
<td>US015</td>
<td>Product Recommendations</td>
<td>Low</td>
<td>As a User, I want to be presented with product recommendations based on my browsing history and preferences, so that I can discover relevant products for me.</td>
</tr>
</table>

*Table 2: User user stories.*

#### 2.2. Visitor

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Priority**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>US11</td>
<td>Sign-in</td>
<td>High</td>
<td>As a Visitor, I want to sign in, so that I can access privileged information.</td>
</tr>
<tr>
<td>US12</td>
<td>Sign-up</td>
<td>High</td>
<td>As a Visitor, I want to sign up, so that I can authenticate myself into the system.</td>
</tr>
<tr>
<td>US13</td>
<td>Recover Password</td>
<td>Medium</td>
<td>As a Visitor, I want to recover my password, so that I can recover my account.</td>
</tr>
<tr>
<td>US14</td>
<td>OAuth API Sign-up</td>
<td>Low</td>
<td>As a Visitor, I want to register a new account using my Google account, so that I don’t have to go through the process of creating a whole new account for this platform.</td>
</tr>
<tr>
<td>US15</td>
<td>OAuth API Sign-in</td>
<td>Low</td>
<td>As a Visitor, I want to sign in through my Google account, so that I can access privileged information.</td>
</tr>
</table>

*Table 3: Visitor user stories.*

#### 2.3 Customer

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Priority**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>US21</td>
<td>Sign out</td>
<td>High</td>
<td>As a Customer, I want to sign out, so that I can ensure the privacy of my account when using public devices.</td>
</tr>
<tr>
<td>US22</td>
<td>Checkout purchase</td>
<td>High</td>
<td>As a Customer, I want to finalize my purchase so that I can receive the products I bought.</td>
</tr>
<tr>
<td>US23</td>
<td>Edit Profile</td>
<td>High</td>
<td>As a Customer, I want to edit my account information, so that I can keep my account up to date.</td>
</tr>
<tr>
<td>US24</td>
<td>View Profile</td>
<td>High</td>
<td>As a Customer, I want to access and view my user profile information, so that I can manage and review my account information easily.</td>
</tr>
<tr>
<td>US25</td>
<td>Order History</td>
<td>High</td>
<td>As a Customer, I want to access my order history so that I can track previous purchases and reorder items if needed.</td>
</tr>
<tr>
<td>US26</td>
<td>Change of Order Notification</td>
<td>Medium</td>
<td>As a Customer, I want to receive a notification when there is a change in the processing order, change in prices of items in my cart or items on my favorites becoming available, so that I can stay updated on the progress of my orders and the state of products I want to buy.</td>
</tr>
<tr>
<td>US27</td>
<td>Change in Price Notification</td>
<td>Medium</td>
<td>As a Customer, I want to receive a notification when there is a change in the prices of items in my cart, so that I can stay aware of the price I’m paying for the items I’m buying.</td>
</tr>
<tr>
<td>US28</td>
<td>Item availability Notification</td>
<td>Medium</td>
<td>As a Customer, I want to receive a notification when items on my favorites become available, so that I can be aware that I can buy the item I wanted.</td>
</tr>
<tr>
<td>US29</td>
<td>Payment approved Notification</td>
<td>Medium</td>
<td>As a Customer, I want to receive a notification when my payment is approved, so that I can be confident that my transaction went through.</td>
</tr>
<tr>
<td>US210</td>
<td>Add to favorites</td>
<td>Medium</td>
<td>As a Customer, I want to add items to my favorites list, so that I can easily access and track products I'm interested in.</td>
</tr>
<tr>
<td>US211</td>
<td>Delete Account</td>
<td>Medium</td>
<td>As a Customer, I want the option to permanently delete my account, so that I can close my account and remove my data from the system.</td>
</tr>
<tr>
<td>US212</td>
<td>Order History</td>
<td>Medium</td>
<td>As a Customer, I want to access my order history so that I can track previous purchases and reorder items if needed.</td>
</tr>
<tr>
<td>US213</td>
<td>Secure Payments</td>
<td>Low</td>
<td>As a Customer, I want to use secure methods of payment, so that I make my purchase with confidence.</td>
</tr>
<tr>
<td>US214</td>
<td>Select delivery options</td>
<td>Low</td>
<td>As a Customer I want to select a delivery time, so that I can get the most convenient option for receiving my order.</td>
</tr>
<tr>
<td>US215</td>
<td>View and Apply Coupons</td>
<td>Low</td>
<td>As a Customer, I want to apply discount coupons during the checkout process, so that I can save money on my purchases.</td>
</tr>
<tr>
<td>US216</td>
<td>Like Reviews</td>
<td>Low</td>
<td>As a Customer, I want to like other users reviews so that I can help promote well-written reviews within the community.</td>
</tr>
<tr>
<td>US217</td>
<td>Report Review</td>
<td>Low</td>
<td>As a Customer, I want to report a review that contains inappropriate content, so that I can help maintain the quality and integrity of product reviews on the platform.</td>
</tr>
</table>

*Table 4: Customer user stories.*

#### 2.4. Administrator:

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Priority**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>US31</td>
<td>Add new product</td>
<td>High</td>
<td>As an Administrator, I want to be able to add new products to the online shop, so that I can make new items available for customers to purchase.</td>
</tr>
<tr>
<td>US32</td>
<td>Remove a product</td>
<td>High</td>
<td>As an Administrator, I want to be able to remove products from the online shop, so that I can make old items unavailable for customers to purchase.</td>
</tr>
<tr>
<td>US33</td>
<td>Manage Products Information</td>
<td>High</td>
<td>As an Administrator, I want to have the capability to update and manage product information like name, descriptions, prices, so that I can ensure that product information is up to date.</td>
</tr>
<tr>
<td>US34</td>
<td>Manage Product Categories</td>
<td>High</td>
<td>As an Administrator, I want to manage product categories, including creating new categories, , so that I can organize the products available in the website.</td>
</tr>
<tr>
<td>US35</td>
<td>Manage Users</td>
<td>Medium</td>
<td>As an Administrator, I want the capability to view, edit, block, unblock or delete user accounts so that I can maintain the security and integrity of the platform.</td>
</tr>
<tr>
<td>US36</td>
<td>Manage Product Stock</td>
<td>Medium</td>
<td>As an Administrator, I want to manage product stock levels, so that I can ensure that products are available for customers to purchase.</td>
</tr>
<tr>
<td>US37</td>
<td>Delete Inappropriate Reviews</td>
<td>Low</td>
<td>As an Administrator, I want the ability to delete customer reviews that contain inappropriate content, so that I can maintain a safe environment in the platform.</td>
</tr>
<tr>
<td>US38</td>
<td>View Sales Statistics</td>
<td>Low</td>
<td>As an Administrator, I want to view sales statistics, so that I can optimize my business.</td>
</tr>
<tr>
<td>US39</td>
<td>Manage Discounts</td>
<td>Low</td>
<td>As an Administrator, I want to manage product discounts and promotions, so that I can attract customers with special offers.</td>
</tr>
</table>

*Table 5: Administrator user stories.*

#### 2.5. Review Author:

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Priority**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>US41</td>
<td>Review Product</td>
<td>High</td>
<td>As a Review Author, I want to be able to review and provide feedback on products, so that I can share my experiences with other customers.</td>
</tr>
<tr>
<td>US42</td>
<td>Edit Review</td>
<td>Medium</td>
<td>As a Review Author, I want the ability to edit a review that I have previously submitted so that I can update my feedback in case my opinion has changed.</td>
</tr>
<tr>
<td>US43</td>
<td>Remove Review</td>
<td>Medium</td>
<td>As a Review Author, I want the ability to remove a review I've done previously, so that I can retract my feedback in case I no longer want it displayed.</td>
</tr>
<tr>
<td>US44</td>
<td>Notifications on Review Feedback</td>
<td>Low</td>
<td>As a Review Author, I want to receive notifications when other users like my reviews, so that I stay informed when others agree with my feedback.</td>
</tr>
</table>

*Table 6: Review Author user stories.*

### 3\. Supplementary Requirements

Section including business rules, technical requirements, and restrictions.

#### 3.1. Business Rules

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>BR01</td>
<td>Deleted Account</td>
<td>

When an account is deleted, any shared user data is kept but is made anonymous
</td>
</tr>
<tr>
<td>BR02</td>
<td>Order Cancellation</td>
<td>Customers should not be allowed to cancel an order once it is out for delivery.</td>
</tr>
<tr>
<td>BR03</td>
<td>Account Independency</td>
<td>Administrators accounts are independent of the user accounts, they cannot buy products.</td>
</tr>
<tr>
<td>BR04</td>
<td>Single Review</td>
<td>Customers should only be able to have one review per product.</td>
</tr>
<tr>
<td>BR05</td>
<td>Age Restriction</td>
<td>Customers should verify their age when buying products like alcoholic beverages or tobacco.</td>
</tr>
<tr>
<td>BR06</td>
<td>Review Date validation</td>
<td>When a product review is submitted, the system must validate that the review date provided is not set to a date later than the current date and time.</td>
</tr>
<tr>
<td>BR07</td>
<td>Own review feedback</td>
<td>A user who has authored a product review should not have the capability to like or upvote their own review.</td>
</tr>
</table>

*Table 7: Business Rules.*

#### 3.2. Technical Requirements

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>

**TR01**
</td>
<td>

**Performance**
</td>
<td>

**The system should have response times shorter than 2s to ensure the user has a good experience when navigating the website.**

**Performance shapes user satisfaction and retention. Slow response times can make the user frustrated, and as so, the faster and more responsive the system is, the more customers it can retain.**
</td>
</tr>
<tr>
<td>TR02</td>
<td>Robustness</td>
<td>The system must be prepared to handle any errors that may occur.</td>
</tr>
<tr>
<td>TR03</td>
<td>Scalability</td>
<td>The system must be prepared to deal with an increasing number of users.</td>
</tr>
<tr>
<td>TR04</td>
<td>Accessibility</td>
<td>The system must ensure that everyone can access the pages, regardless of their capabilities.</td>
</tr>
<tr>
<td>

**TR06**
</td>
<td>

**Security**
</td>
<td>

**The system must protect sensitive information and user data from unauthorized access via an authentication system.**

**Ensuring the protection of sensitive information is of utmost importance and responsibility for any online platform, particularly when it involves handling user data and financial transactions.**
</td>
</tr>
<tr>
<td>TR07</td>
<td>Availability</td>
<td>The system must be up the entire day at any given day.</td>
</tr>
<tr>
<td>TR07</td>
<td>Compatibility</td>
<td>The website should be compatible with a range of web browsers, including Chrome, Firefox, Safari, Edge, Opera etc.</td>
</tr>
<tr>
<td>TR08</td>
<td>Web Application</td>
<td>The system should be developed as a web application using HTML, JavaScript, CSS3 and PHP.</td>
</tr>
<tr>
<td>

**TR09**
</td>
<td>

**Portability**
</td>
<td>

**The system should work on any given operating system (Linux, Windows, MacOS, etc.).**

**Cross-platform compatibility is crucial for broad accessibility and usability. The wider the range, the higher the number of customers.**
</td>
</tr>
<tr>
<td>TR10</td>
<td>Database</td>
<td>The database management system used must be PostgreSQL, version 11 or higher.</td>
</tr>
<tr>
<td>TR11</td>
<td>Ethics</td>
<td>The system should adhere to ethical principles in software development, which means that it shouldn’t collect or share personal user details without obtaining authorization from the respective data owners.</td>
</tr>
</table>

*Table 8: Technical Requirements.*

#### 3.3. Restrictions

<table>
<tr>
<td>

**Identifier**
</td>
<td>

**Name**
</td>
<td>

**Description**
</td>
</tr>
<tr>
<td>C01</td>
<td>Deadline</td>
<td>The website should be ready for use by the end of the semester.</td>
</tr>
</table>

*Table 9: Restrictions.*

## A3: Information Architecture

This aims to share information in order to enhance user understanding, navigation, and content findability within the system through a sitemap and wireframes.

### 1\. Sitemap

Sitemap presenting the overall structure of the web application.

![aaa](uploads/1d7a83de97e5c83f4f9cf3ab863fd8f7/aaa.jpg)

*Figure 2: Sitemap.*

### 2\. Wireframes

Following images are wireframes demonstrating what structure some of our pages will have.

#### UI01: Main Page

![image](uploads/8bd9d50ea01d9131b71afba01de96a77/image.png)

*Figure 3: Main Page.*

#### UI02: Product Page

![Image](uploads/7c585bbf634c8787de167fdf7f836ac6/Image.png)

*Figure 4: Product Page.*

#### UI03: Search Page

![image](uploads/b8ede075497b88271683c84cf3721446/image.png)

*Figure 5: Search Page.*

## Revision History

Changes made to the first submission:


**GROUP45, 01/10/2023**

- Carlos Manuel da Silva Costa
  - Email: up202004151@up.pt
- João Pedro Rodrigues Coutinho
  - Email: up202108787@up.pt
- Miguel Jorge Medeiros Garrido
  - Email: up202108889@up.pt
- Tomás Henrique Ribeiro Coelho
  - Email: up202108861@up.pt

**Editor**: Miguel Garrido