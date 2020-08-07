Feature: Create a TodoList

  Scenario: Creating a simple todo list
    When I send a "POST" request to "/lists" with body:
    """
    {
      "name": "foo"
    }
    """
    Then the response should be in JSON
    And I found "name" with value "foo" in the response
    And I found "createdAt" in the response

  Scenario: Creating a todo list with points
    When I send a "POST" request to "/lists" with body:
    """
    {
      "name": "foo",
      "points": [
          {
            "name": "bar",
            "position": 1,
            "description": "this is a description"
          }
      ]
    }
    """
    Then the response should be in JSON
    And I found "name" with value "foo" in the response
    And I found "id" in the response
    And I found "createdAt" in the response
    And I found "points.0.name" with value "bar" in the response
    And I found "points.0.description" with value "this is a description" in the response
    And I found "points.0.id" in the response
    And I found "points.0.createdAt" in the response