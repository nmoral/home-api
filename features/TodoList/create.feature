Feature: Create a TodoList

  Scenario: Creating a simple todo list
    When I send a "GET" request to "/1" with body:
    """
    {"name": "foo"}
    """
    Then the response should be in JSON
    And I found "name" with value "foo" in the response