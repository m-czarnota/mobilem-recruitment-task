Feature: Get announcement file controller tests

  Scenario: User get file
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
    When I open "GET" page "/api/v1/announcements-files/example-announcement-1/example-announcement-file-1"
    Then the response with code "200" should be received

  Scenario: User tries to get announcement file, but the file does not exist
    When I open "GET" page "/api/v1/announcements-files/example-announcement-1234/example-announcement-file-23461"
    Then the response with code "404" should be received