Feature: Add announcement controller tests

  Scenario: User adds an announcement successfully
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" with form data as logged user
    """
    {
      "title": "I will sell Opel",
      "description": "Great occasion! Only 100 onions!",
      "cost": 100
    }
    """
    Then the response with code "201" should be received

  Scenario: User add an announcements with one file successfully
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" with form data with files as logged user
    """
    {
      "title": "I will sell Opel",
      "description": "Great occasion! Only 100 onions!",
      "cost": 100,
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "201" should be received

  Scenario: User tries to add an announcement with too large file and he gets not acceptable code in response with errors
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" with form data with files as logged user
    """
    {
      "title": "I will sell Opel",
      "description": "Great occasion! Only 100 onions!",
      "cost": 100,
      "files": [
        {
          "path": "/File/large-image.jpg"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "files": [
        {
          "size": "File is too large, allowed size: `2` MB, current size: `2.50` MB"
        }
      ]
    }
    """

  Scenario: User tries to add an announcement with non image file and he gets not acceptable code in response with errors
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" with form data with files as logged user
    """
    {
      "title": "I will sell Opel",
      "description": "Great occasion! Only 100 onions!",
      "cost": 100,
      "files": [
        {
          "path": "/File/logs.txt"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "files": [
        {
          "mimeType": "File is not image"
        }
      ]
    }
    """

  Scenario: User tries to add an announcement with 6 images and he gets not acceptable code in response with error
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" with form data with files as logged user
    """
    {
      "title": "I will sell Opel",
      "description": "Great occasion! Only 100 onions!",
      "cost": 100,
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "generalError": "Announcement cannot have more than 1 files"
    }
    """

  Scenario: Users tries to add an announcement with empty response content and he gets bad request code in response with errors
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" as logged user
    Then the response with code "400" should be received
    And the response should looks like
    """
    {
      "title": "Missing `title` parameter",
      "description": "Missing `description` parameter",
      "cost": "Missing `cost` parameter"
    }
    """

  Scenario: User tries to add an announcement with invalid data and he gets bad request code in response with errors
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "POST" page "/api/v1/user/announcements" with form data with files as logged user
    """
    {
      "title": "I will sell Opel I will sell Opel I will sell Opel I will sell Opel I will sell Opel",
      "description": "",
      "cost": -4,
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "title": "Title cannot be longer than 80 characters",
      "description": "Description cannot be empty",
      "cost": "Cost cannot be negative"
    }
    """

  Scenario: User tries to add an announcement as not authorized user and ge gets unauthorized core in response
    When I open "POST" page "/api/v1/user/announcements" with form data with files
    """
    {
      "title": "I will sell Opel I will sell Opel I will sell Opel I will sell Opel I will sell Opel",
      "description": "",
      "cost": -4,
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "401" should be received