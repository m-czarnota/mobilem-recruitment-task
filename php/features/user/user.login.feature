Feature: User login controller tests

  Scenario: User login successfully
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/login" with form data
    """
    {
      "email": "example@user.com",
      "password": "abc123"
    }
    """
    Then the response with code "200" should be received

  Scenario: User tries to sign in with not existed email
    When I open "POST" page "/api/v1/login" with form data
    """
    {
      "email": "not-existed-email@email",
      "password": "abc123"
    }
    """
    Then the response with code "406" should be received