Feature: User Account

  Scenario: As a user I would like to be able to get a restful response when logout
    When I issue a "GET" request to "/hello/world"
    Then The Response Code will be "200"