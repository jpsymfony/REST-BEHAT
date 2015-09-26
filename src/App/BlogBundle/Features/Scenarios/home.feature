Feature: Homepage

Background:
    When I am on homepage

Scenario:
    Then I should see "Accueil"
    Then I click on "#categories"
    And I move forward one page
    Then I should see "jpSymfony & optimusThePrime 2015"