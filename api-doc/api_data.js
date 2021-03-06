define({ "api": [
  {
    "type": "post",
    "url": "fblogin/",
    "title": "Facebook Login",
    "name": "fblogin",
    "group": "User",
    "parameter": {
      "fields": {
        "User": [
          {
            "group": "User",
            "type": "string",
            "optional": false,
            "field": "facebook_data",
            "description": "<p>Facebook Token and Data - Required</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "  {\n  \"data\": {\n    \"id\": 9,\n    \"token\": \"eyJ0eXAiOiJKxxxV1QiLCJhbGciO11NiJ9.eyJzdWIiOjksImlzcyI6I1mh0dHA6XC9cLzUyLjY2LjczLjEyN1wvc3Bvcn2R5YXBwXC9wdWJsaWNcL2FwaVwvZmJsb2dpbiIsImlhdCI6MTQ5NDAxMTI5MSwiZXhwIjoxNTI1NTQ3MjkxLCJuYmYiOjE0OTQwMTEyOTEsImp0aSI6Ikk1YXRnYzI0Q3hNaDZwTFEifQ.nasV5Duah5c3oZV3Ev41AOOsOwCEeGmieb38OLD5Ygw\",\n    \"username\": \"\",\n    \"name\": \"Anuj Jaha\",\n    \"email\": \"er.anujjaha@gmail.com\",\n    \"location\": \"Ahmedabad, India\",\n    \"image\": \"\"\n  },\n  \"message\": \"Success\",\n  \"code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n  \"message\": \"Invalid Arguments\",\n  \"code\": \"500\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "sporty-api/example.js",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://52.66.73.127/sportyapp/public/api/fblogin/"
      }
    ]
  },
  {
    "type": "post",
    "url": "login/",
    "title": "Login",
    "name": "login",
    "group": "User",
    "parameter": {
      "fields": {
        "User": [
          {
            "group": "User",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>User Name  - Required</p>"
          },
          {
            "group": "User",
            "type": "password",
            "optional": false,
            "field": "password",
            "description": "<p>User Secret - Required</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "\t{\n    \"data\": {\n    \"id\": 8,\n    \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjgsImlzcyI6Imh0dHA6XC9cLzUyLjY2LjczLjEyN1wvc3BvcnR5YXBwXC9wdWJsaWNcL2FwaVwvbG9naW4iLCJpYXQiOjE0OTQwMTA3ODMsImV4cCI6MTUyNTU0Njc4MywibmJmIjoxNDk0MDEwNzgzLCJqdGkiOiJmVWxGdzRaem1BamU3SmJ3In0.eoWDC8UxAoDcR76F5LgHjO5fyT_m_cx3vYvfS5wBJz4\",\n    \"username\": \"anuj12\",\n    \"name\": \"Anuj Jaha\",\n    \"email\": \"er.anujjaha@gmail.com\",\n    \"location\": \"\",\n    \"image\": \"\"\n  },\n  \"message\": \"Success\",\n  \"code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n  \"message\": \"invalid_credentials\",\n  \"code\": \"500\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "sporty-api/example.js",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://52.66.73.127/sportyapp/public/api/login/"
      }
    ]
  },
  {
    "type": "post",
    "url": "register/",
    "title": "Registration",
    "name": "register",
    "group": "User",
    "parameter": {
      "fields": {
        "User": [
          {
            "group": "User",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>User Name  - Required</p>"
          },
          {
            "group": "User",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>User Email Id - Required</p>"
          },
          {
            "group": "User",
            "type": "password",
            "optional": false,
            "field": "password",
            "description": "<p>User Secret - Required</p>"
          },
          {
            "group": "User",
            "type": "username",
            "optional": false,
            "field": "username",
            "description": "<p>Username - Required</p>"
          },
          {
            "group": "User",
            "type": "file",
            "optional": true,
            "field": "image",
            "description": "<p>User Profile Picture</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "\t{\n  \"data\": {\n    \"id\": 12,\n    \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyLCJpc3MiOiJodHRwOlwvXC81Mi42Ni43My4xMjdcL3Nwb3J0eWFwcFwvcHVibGljXC9hcGlcL3JlZ2lzdGVyIiwiaWF0IjoxNDk0MDExMDUzLCJleHAiOjE1MjU1NDcwNTMsIm5iZiI6MTQ5NDAxMTA1MywianRpIjoiZ0lid0V6bU1RR095UW9ZUSJ9.VwkEE8uyYOFFj1pQA8FUWo2PfEAdRTT7-u8-4GYNo_M\",\n    \"username\": \"anuj12\",\n    \"name\": \"Anuj\",\n    \"email\": \"er.anujjaha@gmail.com\",\n    \"location\": \"\",\n    \"image\": \"\"\n  },\n  \"message\": \"Success\",\n  \"code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"message\": \"User's Email Already Exist\",\n    \"code\": \"500\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "sporty-api/example.js",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://52.66.73.127/sportyapp/public/api/register/"
      }
    ]
  },
  {
    "type": "get",
    "url": "users/getdata/",
    "title": "Get Single User",
    "name": "user_getdata",
    "group": "User",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": " {\n  \"data\": {\n    \"id\": 11,\n    \"username\": \"anuj12\",\n    \"name\": \"Anuj Jaha\",\n    \"email\": \"er.anujjaha@gmail.com\",\n    \"location\": \"Ahmedabad, India\",\n    \"image\": \"http://52.66.73.127/sportyapp/public/uploads/users/74880.jpg\"\n  },\n  \"message\": \"Success\",\n  \"code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n  \"data\": {\n    \"success\": false,\n    \"message\": \"Invalid Token - Wrong Token !\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "sporty-api/example.js",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://52.66.73.127/sportyapp/public/api/users/getdata/"
      }
    ]
  },
  {
    "type": "post",
    "url": "users/getlist/",
    "title": "Get User List",
    "name": "user_getlist",
    "group": "User",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "User": [
          {
            "group": "User",
            "type": "string",
            "optional": true,
            "field": "search",
            "description": "<p>Filter by username - Optional</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": " {\n  \"data\": [\n    {\n      \"id\": 12,\n      \"username\": \"anuj12\",\n      \"name\": \"Anuj\",\n      \"email\": \"er.anujjaha@gmail.com\",\n      \"location\": \"\",\n      \"image\": \"\"\n    },\n    {\n      \"id\": 3,\n      \"username\": \"user\",\n      \"name\": \"Default User\",\n      \"email\": \"user@user.com\",\n      \"location\": \"\",\n      \"image\": \"\"\n    },\n  ],\n  \"message\": \"Success\",\n  \"code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n  \"data\": {\n    \"success\": false,\n    \"message\": \"Invalid Token - Wrong Token !\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "sporty-api/example.js",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://52.66.73.127/sportyapp/public/api/users/getlist/"
      }
    ]
  },
  {
    "type": "post",
    "url": "users/update/",
    "title": "Update Name",
    "name": "users_update",
    "group": "User",
    "header": {
      "fields": {
        "Authorization": [
          {
            "group": "Authorization",
            "type": "String",
            "optional": false,
            "field": "authorization",
            "description": "<p>Authorization value.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "User": [
          {
            "group": "User",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>User Name  - Required</p>"
          },
          {
            "group": "User",
            "type": "string",
            "optional": true,
            "field": "location",
            "description": "<p>User Location - optional</p>"
          },
          {
            "group": "User",
            "type": "file",
            "optional": true,
            "field": "image",
            "description": "<p>User Profile Picture - optional</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "    {\n  \"data\": [],\n  \"message\": \"Success\",\n  \"code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n  \"data\": {\n    \"success\": false,\n    \"message\": \"Invalid Token - Wrong Token !\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "sporty-api/example.js",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://52.66.73.127/sportyapp/public/api/users/update/"
      }
    ]
  }
] });
