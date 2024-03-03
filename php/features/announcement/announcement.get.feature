Feature: Get announcement controller tests

  Scenario: User gets announcement successfully
    Given there exist an announcement like
    """
    {
      "id": "example-announcement-1",
      "title": "I will sell Opel",
      "description": "Great occasion! Only 100 onions!",
      "cost": 100,
      "files": [
        {
          "id": "example-announcement-file-1",
          "name": "teddy-bear.jpg",
          "path": "tests/Common/File/teddy-bear.jpg"
        }
      ]
    }
    """
    When I open "GET" page "/api/v1/announcements/example-announcement-1"
    Then the response with code "200" should be received

  Scenario: User tries to get non existed announcement and he gets not found code in response
    When I open "GET" page "/api/v1/announcements/non-existed-example-announcement-1"
    Then the response with code "404" should be received
