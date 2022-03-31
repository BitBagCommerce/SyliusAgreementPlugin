@customer_registration
Feature: Customer registration
    In order to make future purchases with ease
    As a Visitor
    I need to be able to create an account in the store

    Background:
        Given the store operates on a single channel in "United States"
        Given the store has agreement "REGISTRATION_AGREEMENT" in context "registration_form"

    @ui
    Scenario: Registering a new customer with checking required agreement
      When I want to register a new account
      And I specify the first name as "John"
      And I specify the last name as "Cena"
      And I specify the email as "johncena@example.com"
      And I specify the password as "password"
      And I confirm this password
      And I check agreement "REGISTRATION_AGREEMENT" during registration
      And I register this account
      Then I should be notified that new account has been successfully created
