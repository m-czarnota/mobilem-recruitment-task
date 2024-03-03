Feature: List announcement controller tests

  Scenario: User gets announcements successfully
    When I open "GET" page "/api/v1/announcements"
    Then the response with code "200" should be received
