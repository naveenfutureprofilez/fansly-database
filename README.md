# WhoYouInto backend
## APIs

CSRF COOKIE - GET
```
/csrf-cookie
```
Check Username - post {username: "username"}
```
/check-username
```

## Done : Login - POST
```
/login
```
Register - POST
```
/register
```

Logout - POST
```
/user/logout
```
Loggen In User Data - GET
```
/user
```

## Creator Profile Request
Request Type - POST
```
/creator-request
```
### Required Data
verify_img -> Image less then 3MB, jpg,png, bmp allowed. 
```
{
    address : {
        street : "street address",
        city   : "City Name",
        state  : "State name"
        country: "Country Name"
        zip    : "ZipCode"
    },
    social: {
        platform1 : "Profile URl",
        platform2 : "Profile URl",
    },
    id_type: "Type of Id",
    id_no: "ID Number",
    id_expire: 1|0,
    id_expiry: null|date,
    verify_img: "Uploaded Image Data"
}
```

### Check Pending Requests
Request Type - GET
```
/requests
```

### Upload Temp File
Request Type - POST
Field name - file
```
/upload-file
```

### Upload Multiple Temp Files
Request Type - POST
Field name - files[]
```
/upload-multi-file
```

## Creator Section APIs
### Create a Plan
Request Type -  POST
```
/creator/create-plan
```
Data Format
Optional params: promotion, month_2, month_3, month_3, prom_amount(require if promotion true), avail_from(require if promotion true), avail_to(require if promotion true),

```
{
    title : "Title of Plan"
    amount: "Amount of the Plan",
    benefits: "Blank or content seperated by new line",
    month_2: {
        off : percent off,
        amount: discounted amount
    },
    month_3: {
        off : percent off,
        amount: discounted amount
    },
    month_6: {
        off : percent off,
        amount: discounted amount
    },
    promotion: 0|1,
    prom_amount: "Amount of plan during promotion",
    avail_from: date,
    avail_to : date
}
```
Response will be:
```
{
    "status": true,
    "msg": "Okay",
    "plan": "eyJpdiI6Im1yNTRrZ2JTVzZhQ2FDb0k1MmlzcUE9PSIsInZhbHVlIjoiVmFTUk45d1NUcFh3VTY3TFFZb0p4Zz09IiwibWFjIjoiYzI1N2NlOTU1ZDE4MTM4ZGZlYTM1YmM1MzRhYWI0ZDJiOGM2NzJmMTgyNTAwYzJkNWIyYmY4ZmNjMmQ5NjJkNyIsInRhZyI6IiJ9",
    "prom": "eyJpdiI6IlVaSUhBallJUjZuZ3Y2Tlprb0FzaUE9PSIsInZhbHVlIjoiQUh6ZU5ZbGZETllFbitiNnRaUEFWZz09IiwibWFjIjoiOTM0ZmM3ZGJhMDZhY2Q2YWQ1ODAzOTI4Y2M5ODk0ZTExOGQzOTU1OWM4OTFjMDczNTYyOTNmNDliN2EzYTA3MiIsInRhZyI6IiJ9"
}
```

### Get A plan by encrypted ID
Request Type - GET
```
/creator/get-plan/{planId}
```
Response:
```
{
    "status": true,
    "msg": "Okay",
    "plan": {
        "title": "Subscription 1",
        "amount": "5.00",
        "benefits": [
            "Hello",
            "There",
            "How",
            "Are you"
        ],
        "month_2": {
            "off": "20",
            "amount": "4"
        },
        "month_3": null,
        "month_6": null,
        "status": 1,
        "promotion": [
            {
                "id": 2,
                "prom_amount": "3",
                "avail_from": "2022-02-17",
                "avail_to": "2022-02-18",
                "status": 1
            }
        ]
    }
}
```

### User Profile
Request Type - GET
```
/profile/{userId}
```
Response:
```
{
    "status": true,
    "msg": "Okay",
    "profile": {
        "id": 31,
        "username": "naveen_",
        "name": "Naveen Tehp",
        "email": "naveen_@fpdemo.com",
        "phone": "9876543210",
        "avatar": "users/default.png",
        "email_verified_at": null,
        "settings": null,
        "balance": 0,
        "isAdmin": "No",
        "role": 0,
        "ip": null,
        "isBanned": "No",
        "bio": null,
        "created_at": "2022-02-11T06:46:28.000000Z",
        "updated_at": "2022-02-11T06:46:28.000000Z"
    },
    "likes": 2,
    "followers": 2,
    "videos": 0,
    "images": 0
}
```


### User Profile Data
Request Type - GET
```
/user/profile/{encUserid}
```
Response
```
{
    "uid": "eyJpdiI6IlFtOUlEQW5BUUwxT1JwNWViVnVvTFE9PSIsInZhbHVlIjoiWVI2c0ZHN0JCeVpPMnc1REpmWCtjUT09IiwibWFjIjoiZGExMjYxNTkzMDgyN2M4ZmY5MzcyMTA2ZDUyNmI0NTdmNDg5ODk2ZjNkYTQ3YjQ4MmU4ZjA2NDc2NjY4ZjY4YSIsInRhZyI6IiJ9",
    "name": "Eclipsio",
    "username": "naveen_",
    "avatar": "banner_31_1645790159.png",
    "banner": "default.png",
    "role": 0,
    "pro": 0,
    "likes": 2,
    "followers": 1,
    "videos": 0,
    "images": 4
}
```

### Creator - List All Plans
***For logged In Creator***
Request Type - GET
```
/creator/plans
```
Response
```
{
    "status": true,
    "msg": "Okay",
    "plans": [
        {
            "uid": "eyJpdiI6InZXV3k1dzJ2RFhzWGhXdG1OZWlwckE9PSIsInZhbHVlIjoiVkJYSGlvb2FzdktaTVVROUdsTGVKdz09IiwibWFjIjoiZDViZGY5ZDJlYjg4MDI5OTg5OGJlZTY2YTlhNDQ4MTYwZDhlYjlkNjNjZTZhNmYyMzEyMzVmOWM5MWQ0NGI3NCIsInRhZyI6IiJ9",
            "title": "Subscription 1",
            "amount": "5.00",
            "benefits": "Hello\nThere\nHow\nAre you",
            "month_2": {
                "off": "20",
                "amount": "4"
            },
            "month_3": null,
            "month_6": null,
            "status": 1,
            "create": "4 days ago",
            "update": "4 days ago",
            "prom_active": true,
            "prom_exist": 1,
            "promotions": [
                {
                    "uid": "eyJpdiI6IkVQSG1JSlNnSHVqN1RmSnVTSjVzUHc9PSIsInZhbHVlIjoiMVdid09nbG9JanZDaDZIYVA3TjZHUT09IiwibWFjIjoiM2VjOTRiOGJiZjhjMTM3YjliZDc1MzRmMDZhMjNkOTM4ZjBmM2RiYzhmODVhMmVjZWI0Nzg4MGY1NDY0YmZkMCIsInRhZyI6IiJ9",
                    "amount": null,
                    "start": "17-02-2022",
                    "end": "18-02-2022",
                    "status": 1
                }
            ]
        },
        {
            "uid": "eyJpdiI6ImI2U0lhODdlcjc5dzdGajRGbWtPWWc9PSIsInZhbHVlIjoiclR3c3MvR2pEUVFUUCtubUY3eVRzUT09IiwibWFjIjoiZGI4MjljY2MxZWFmOWNhZWIyMzBmNDY0NmFlYzRjNzlkZDY4MjE5M2UyMDMzZjVhZmY5ZDU2NmE3ZTE4NTczYSIsInRhZyI6IiJ9",
            "title": "Subscription 1",
            "amount": "5.00",
            "benefits": "Hello\nThere\nHow\nAre you",
            "month_2": {
                "off": "20",
                "amount": "4"
            },
            "month_3": null,
            "month_6": null,
            "status": 1,
            "create": "4 days ago",
            "update": "4 days ago",
            "prom_active": true,
            "prom_exist": 1,
            "promotions": [
                {
                    "uid": "eyJpdiI6IitOZmFkUWpiVEE2Z05ub0gvM0c3QlE9PSIsInZhbHVlIjoicVk1Z2t6VDJhclVhUE9DYVVuK1kwQT09IiwibWFjIjoiOTU5ZDNlNzc1NTM0ODk5MDE0NTVlOTZlOGQwODEyOTUwMDgzOWJlYTE4ZGY2ZDc5ZDg3YWQwYzFiZDVlYzU1MCIsInRhZyI6IiJ9",
                    "amount": null,
                    "start": "17-02-2022",
                    "end": "18-02-2022",
                    "status": 1
                }
            ]
        },
        {
            "uid": "eyJpdiI6InF0aWc4OG9ORmtUTk1uYlBHWWVtbnc9PSIsInZhbHVlIjoiV3FtNVJPWEMyOWlHdWlrRUcxUjk1UT09IiwibWFjIjoiYmU1NDEzMGNjYTdmOGRmYmNlOTA5ZTM1Zjg2ZmRhZjEzZDc0MDA3Y2FmYzM4N2RiMTgzZjhiNTdlZWY3NjUzOCIsInRhZyI6IiJ9",
            "title": "Subscription 1",
            "amount": "5.00",
            "benefits": "Hello\nThere\nHow\nAre you",
            "month_2": {
                "off": "20",
                "amount": "4"
            },
            "month_3": null,
            "month_6": null,
            "status": 1,
            "create": "4 days ago",
            "update": "4 days ago",
            "prom_active": false,
            "prom_exist": 0,
            "promotions": []
        }
    ]
}
```
***For Users to see Creator Plans***
Request Type - GET
```
/creator-plans/{encUserId}
```
Response
```
{
    "status": true,
    "msg": "Okay",
    "plans": [
        {
            "uid": "eyJpdiI6InZXV3k1dzJ2RFhzWGhXdG1OZWlwckE9PSIsInZhbHVlIjoiVkJYSGlvb2FzdktaTVVROUdsTGVKdz09IiwibWFjIjoiZDViZGY5ZDJlYjg4MDI5OTg5OGJlZTY2YTlhNDQ4MTYwZDhlYjlkNjNjZTZhNmYyMzEyMzVmOWM5MWQ0NGI3NCIsInRhZyI6IiJ9",
            "title": "Subscription 1",
            "amount": "5.00",
            "benefits": "Hello\nThere\nHow\nAre you",
            "month_2": {
                "off": "20",
                "amount": "4"
            },
            "month_3": null,
            "month_6": null,
            "create": "4 days ago",
            "update": "4 days ago",
            "prom_active": true,
            "prom_exist": 1,
            "promotions": [
                {
                    "uid": "eyJpdiI6IkVQSG1JSlNnSHVqN1RmSnVTSjVzUHc9PSIsInZhbHVlIjoiMVdid09nbG9JanZDaDZIYVA3TjZHUT09IiwibWFjIjoiM2VjOTRiOGJiZjhjMTM3YjliZDc1MzRmMDZhMjNkOTM4ZjBmM2RiYzhmODVhMmVjZWI0Nzg4MGY1NDY0YmZkMCIsInRhZyI6IiJ9",
                    "amount": null,
                    "start": "17-02-2022",
                    "end": "18-02-2022",
                }
            ]
        }
    ]
}
```


### Update Plan Data
Request Type POST
```
/creator/plan-update/{encPlanId}
```
Response
```
{
    "status": true,
    "msg": "Plan Updated Successfully",
    "plan": "eyJpdiI6IitZbnhHZU1KQlNjWE9nR3BJNW5YbkE9PSIsInZhbHVlIjoiWnh3RFdKMkMycUp1UlZpazdBS2txdz09IiwibWFjIjoiMTg0MWFiNTZiZTcxNGNlYTlkYzQ2MTAyNzdhMDI2ZjNiNWE3MjBjMzkxNDg3ODliN2VhMDczN2YxYjM4YjQ4MSIsInRhZyI6IiJ9"
}
```

### Create Plans Promotions
Request Type POST
```
/creator/promotion/create
```
Data Format
```
{
    plan: "Encrypted Plan Id"
    prom_amount: Promotion Amount
    avail_from: Date When Promotion Starts
    avail_to: Date when Promotion Ends
}
```
Response
```
{
    "status": true,
    "msg": "Okay",
    "prom": "eyJpdiI6IlVsUnpDYUh4K2lpSXlDUGVib3oxSlE9PSIsInZhbHVlIjoiR1lUb3M0anNlalRFWHAwNHQzWGhhQT09IiwibWFjIjoiZjQ1MzJjNTdhYWFhNzFiMDg0ZGZkYzlhYzM2YWZhNmE4MjM1OWY3NDUwODZmYWVjYjhmOTI3NWExY2VlZjhiYyIsInRhZyI6IiJ9"
}
```
### Update Promotions Status
Request - GET
```
/creator/plan-promotion-status/{encPromId}
```
Response
```
{
    "status": true,
    "msg": "Promotion Message"
}
```

### Update Promotion
Request type - POST
```
/creator/promotion/update/{encPromId}
```
Data Format
```
{
    plan: "Relative Plan's Encrypted id"
    prom_amount: promotion amount
    avail_from: Start date
    avail_to: End date
    status: 0|1
}
```
Response
```
{
    "status": true,
    "msg": "Promotion updated successfully",
    "prom": "eyJpdiI6Ik9ZYzhGcmhUd0kwc2dsUlFpam1LS2c9PSIsInZhbHVlIjoiTCs4cS9hUFkyQktsdUw0MFRUbm5kUT09IiwibWFjIjoiMjdkNjQ5NjI4ZGEyMTdiMjA2MDYwNDNjODhjOTZmMGRjZTQ4Mzc3YWZkYjBlYjE1NTg4MmJhZTZhNzJkZDE3NCIsInRhZyI6IiJ9"
}
```

### Create A Post
Request Type - POST
```
/creator/post/create
```
Data Format
```
{
    content: "text Content"
    media: Array Of Media files
    preview: Array of Preview Files
    post_schedule : date to publish Post (Optional)
    post_delete: date to disable Post (Optional),
    condition: {
        subscription : [Ids of selected Plans],
        fix_price: Price
    }
}
```

### Update User Profile Image
Request Type -  Post
Field - up_img
```
/update-profile-image
```
Response
```
{
    "status": true,
    "msg": "Profile Picture Updated Successfully.",
    "img": "http://localhost/v1/public/storage/avatar/profile_31_1645787114.png"
}
```

### Update User Banner Image
Request -  Post
Field - up_img
```
/update-profile-banner
```
Response
```
{
    "status": true,
    "msg": "Profile Banner Updated Successfully.",
    "img": "http://localhost/v1/public/storage/banner/banner_31_1645787274.png"
}
```
### Update Profile data
Request - POST
```
/update-profile
```
Form data
```
{
    name: "Full Name",
    phone: "valid phone Number"
    bio: "Bio max 655 chars"
}
```
Response
```
{
    "status": true,
    "msg": "Your data has been updated successfully.",
    "user": {
        "name": "Eclipsio",
        "phone": "7014111000",
        "bio": "The tech geak."
    }
}
```

### Get Posts
Request Type - GET
```
/posts/{all|subscribed}
```
### Follow Suggestions
Request Type - GET
```
/follow-suggestions
```
Response
```
{
    "users": [
        {
            "uid": "eyJpdiI6IllKazdWUEZmTG8ybHRlK3RvelJ3dlE9PSIsInZhbHVlIjoiUWxWV3NLUzY2b202SXJKMTlXWEpiZz09IiwibWFjIjoiMDg1MTk5ZWQzZWYwMTFlZGRjY2NiMTU3N2FhOWNmNjhjMzI1ODNiYzBjOWQ1YTVhMjc2YzlhOTFmNzY2MWQ5MSIsInRhZyI6IiJ9",
            "name": "Naveen Tehp",
            "username": "naveeeeeeee",
            "avatar": "default.png",
            "banner": "default.png",
            "role": 1,
            "pro": 0
        },
        {
            "uid": "eyJpdiI6IlVyZUlvVWpCWStDMVlMT0U4WkEwVHc9PSIsInZhbHVlIjoiU1NXYnNIRVN4VG44cE1rMHU4MjRhZz09IiwibWFjIjoiOWRhZGY4MWUzMzExNDY2ODA0NGU0NGM3YzFhODkzMWRkODBkODc3ZGFiZTk1ZGUyODZiODFlMTFjMzAwYmVlOCIsInRhZyI6IiJ9",
            "name": "Naveen Tehp",
            "username": "naveeee",
            "avatar": "default.png",
            "banner": "default.png",
            "role": 1,
            "pro": 0
        },
        {
            "uid": "eyJpdiI6IlBzSU5FSk1zTjJ0N1krREwzQ3gwQ2c9PSIsInZhbHVlIjoidzVEczMwQTJnNlBUNHdFNE8zdXNzZz09IiwibWFjIjoiZDdlYTRhYmMzNDhjZTY3ZjZkMGU4ODI5NTcwMTFiMzBkZGFhMjczYjk2YTY4MzhhMDU3ZjFiZmVjNzU3NTg5NSIsInRhZyI6IiJ9",
            "name": "Naveen Tehp",
            "username": "naveeeee",
            "avatar": "default.png",
            "banner": "default.png",
            "role": 1,
            "pro": 0
        },
        {
            "uid": "eyJpdiI6IlZYOHRGUEVuN3ZTMUFwZWdPK04yeFE9PSIsInZhbHVlIjoiWlVYTWNmb1dSaGNBaUhaTEZSdmYyQT09IiwibWFjIjoiZTlhN2JiYTVlYjgyYjQ1ODVkM2VhNmZjYTdlZDMzNjlkNzQwNWNmZGY4ZTg0YmRkNmZjOTY0ZWQ2NGU4OWFmOCIsInRhZyI6IiJ9",
            "name": "Naveen Tehp",
            "username": "naveeeeee",
            "avatar": "default.png",
            "banner": "default.png",
            "role": 1,
            "pro": 0
        }
    ]
}
```

### Follow Someone
Request Type - GET
```
/follow/{encId}
```
### Unfollow Someone
Request Type - GET
```
/unfollow/{encId}
```
### Like/Unlike a Post
Request Type - GET
```
/post/like/{encPostId}
```
Response
```
{
    "status": true,
    "msg": "Disliked",
    "like": false
}
```
### Comments of a Post
Request Type - GET
```
/post/comments/{encPostId}
```
Response
```
{
    "status": true,
    "msg": "Comments Loaded",
    "post": "eyJpdiI6IkQwdnJ5NzlKYmxuTlh6QUpoL0Z5SWc9PSIsInZhbHVlIjoibGI3Um5uR2xIVWtVbWFNaEFPaUY5dz09IiwibWFjIjoiOGZlMDg2OWFlNGIyYmY0MmJhM2YyMDQxYTFjMDJmYjljZTViMGZlOWJlNWJmZWU5MjYyN2RkYTFlNWIxNDc4ZCIsInRhZyI6IiJ9",
    "comments": [
        {
            "uid": "eyJpdiI6InFudm9PWXl4NXpuUStoTVU2MjhwdEE9PSIsInZhbHVlIjoiUFppQk9xTmVuVzUrcGdOaTdldFNOZz09IiwibWFjIjoiMGM1NWIwY2U3OGIzZDYyMGQ0ZTIwZjA3MGRhZWZkMTc3MTA0NGU2MjJmODFkYjJjYmNjM2U4NzMwODA4MmVlNCIsInRhZyI6IiJ9",
            "comment": "Hey there what are you doing",
            "time": "41 minutes ago",
            "user": {
                "uid": "eyJpdiI6Ikg3UVYyOURHdkhXVGUzZUsrVllUSGc9PSIsInZhbHVlIjoiRmRxeDRuRWdVcWcvK0o1ZnE2aUY2Zz09IiwibWFjIjoiODE2Mzk4OTU4YzNjYTIwZTU4NDE0MWNmMTFhNzczZGI4ZTU3ZDYyMTY0NDNlZDMyMzFiYTc3ZWI5MGE4ZGVjNSIsInRhZyI6IiJ9",
                "name": "Eclipsio",
                "username": "naveen_",
                "avatat": "banner_31_1645790159.png"
            }
        }
    ],
    "last": 1
}
```

### Comment On A Post
Request Type - POST
```
/post/comment/{encPostId}
```
Form data
```
{
    comment: "Comment text Max 655"
}
```
Response
```
{
    "status": true,
    "msg": "Comment posted.",
    "comment": "eyJpdiI6IndORGdlMHFLWEk2aS9rcTAwalhVeFE9PSIsInZhbHVlIjoiRnZWYTFMNUtrMzhMdU1qZlAreExQZz09IiwibWFjIjoiNWE2NjUyMmI4YTM3MWI0ZTBjODgyMmUyYjVhZmM4YWU2MDUyYzM5YTFjOTdlMzMyN2YyYWE2YWFhYTQxNDFiMCIsInRhZyI6IiJ9"
}
```

### Report A Post
Request Type - POST
```
/post/report/{encPostId}
```
Form data
```
{
    reason: "Radio reason",
    explain: [Optional]
}
```
Response
```
{
    "status": true,
    "msg": "Post report Success"
}
```

### View Post Page Data
Request Type - GET
```
/post/{encPostId}
```
Response
```
{
    "post": {
        "uid": "eyJpdiI6IjJBQUoxRWpXWStSWVRrbVdWQnp5M2c9PSIsInZhbHVlIjoiK21Yb25zTHFFdWY0VlVxZ3ZwM09xQT09IiwibWFjIjoiODQ2N2EwMDEwZTM2ZjY5NDdiZjZjZDljZmMxYjIyMWVhYWQxMzcwNTE2YzdiOTdiMDIwZTUzNDc1ZDc3NTc1ZSIsInRhZyI6IiJ9",
        "content": "sdasd",
        "user": {
            "id": 31,
            "uid": "eyJpdiI6IlJyMGYrTzd1bmJSdVJ6VWsrelBPQWc9PSIsInZhbHVlIjoiTEJWUnhmTE1DM0NLUGFMbmRsNU9OZz09IiwibWFjIjoiMTE4NzcxZTFlZTUyZGM1YmFjM2VhM2ZjZWMwNTZhOTFmZDc0YzM4Zjg3ZTdhNGQ0YmZjOWMyYjZkMzNhZjZkZCIsInRhZyI6IiJ9",
            "name": "Eclipsio",
            "username": "naveen_",
            "role": 0,
            "avatar": "banner_31_1645790159.png",
            "banner": "default.png",
            "likes": 2,
            "followers": 0,
            "videos": 0,
            "images": 4,
            "is_follow": false
        },
        "likes": 1,
        "comments": 2,
        "tips": 120,
        "is_liked": true,
        "posted_at": "6 days ago",
        "media_allow": true,
        "media": [],
        "preview": []
    }
}
```

### Get User Page View Details
Request Type - GET
```
/user/{encUserId}
```
Response
```
{
    "uid": "eyJpdiI6IjhKcjdyUzlhNWY1MU5sc2hqUUxyU1E9PSIsInZhbHVlIjoiV3F0eXoyUnBTejZqTUs2SzhYQUJ1Zz09IiwibWFjIjoiN2E0MGU5NzQxNjk5MTBmZjhmMDQ3NjcyNmVhNzBlYWMwZTAxYmU4ZGRmYzk5YWVmNDdhY2I3ODlhMjA2ZTQwYyIsInRhZyI6IiJ9",
    "name": "Eclipsio",
    "username": "naveen_",
    "avatar": "banner_31_1645790159.png",
    "banner": "default.png",
    "bio": "The tech geak.",
    "role": 0,
    "pro": 0,
    "likes": 2,
    "followers": 0,
    "videos": 0,
    "images": 4,
    "is_follow": false,
    "posts": [
        {
            "uid": "eyJpdiI6ImtGOHlaZVpUY3ZQWUxPQVp1VE9PMmc9PSIsInZhbHVlIjoiMXlrL2NjVVhlMGdtVVNoVDVjQlhNQT09IiwibWFjIjoiNWI5ZThmZTU5NDViMTI3MWJiZjdmN2NhNThlZmZhZDc2YmQzMmE2MzQ4NTc1ZTI3ZjNjYzViZGQ4MTI4NjdlNSIsInRhZyI6IiJ9",
            "content": "sdasd",
            "likes": 1,
            "comments": 2,
            "tips": 120,
            "is_liked": true,
            "posted_at": "6 days ago",
            "media_allow": true,
            "media": [],
            "preview": []
        },
        {
            "uid": "eyJpdiI6InhrdENxOWMzWTVRTnI0b0p1dHJoTHc9PSIsInZhbHVlIjoiRHZpcjVFcFBuODZFMTV3aTRDYVpRZz09IiwibWFjIjoiMWNhNmQwMTRkNmFmZDM1YTExYTNiYjA1NTZmYWE5ZGRmYTgyMjc1ZWQ2MGRhNjMwMmY0YzUxM2QzZjEzZTY3NiIsInRhZyI6IiJ9",
            "content": "easdasdasd",
            "likes": 1,
            "comments": 0,
            "tips": 0,
            "is_liked": true,
            "posted_at": "6 days ago",
            "media_allow": true,
            "media": [],
            "preview": []
        }
    ],
    "posts_last": 6
}
```

### Get User Page View Details BY USERNAME
Request Type - GET
```
/user/u/{username}
```
Response
```
{
    "uid": "eyJpdiI6IjhKcjdyUzlhNWY1MU5sc2hqUUxyU1E9PSIsInZhbHVlIjoiV3F0eXoyUnBTejZqTUs2SzhYQUJ1Zz09IiwibWFjIjoiN2E0MGU5NzQxNjk5MTBmZjhmMDQ3NjcyNmVhNzBlYWMwZTAxYmU4ZGRmYzk5YWVmNDdhY2I3ODlhMjA2ZTQwYyIsInRhZyI6IiJ9",
    "name": "Eclipsio",
    "username": "naveen_",
    "avatar": "banner_31_1645790159.png",
    "banner": "default.png",
    "bio": "The tech geak.",
    "role": 0,
    "pro": 0,
    "likes": 2,
    "followers": 0,
    "videos": 0,
    "images": 4,
    "is_follow": false,
    "posts": [
        {
            "uid": "eyJpdiI6ImtGOHlaZVpUY3ZQWUxPQVp1VE9PMmc9PSIsInZhbHVlIjoiMXlrL2NjVVhlMGdtVVNoVDVjQlhNQT09IiwibWFjIjoiNWI5ZThmZTU5NDViMTI3MWJiZjdmN2NhNThlZmZhZDc2YmQzMmE2MzQ4NTc1ZTI3ZjNjYzViZGQ4MTI4NjdlNSIsInRhZyI6IiJ9",
            "content": "sdasd",
            "likes": 1,
            "comments": 2,
            "tips": 120,
            "is_liked": true,
            "posted_at": "6 days ago",
            "media_allow": true,
            "media": [],
            "preview": []
        },
        {
            "uid": "eyJpdiI6InhrdENxOWMzWTVRTnI0b0p1dHJoTHc9PSIsInZhbHVlIjoiRHZpcjVFcFBuODZFMTV3aTRDYVpRZz09IiwibWFjIjoiMWNhNmQwMTRkNmFmZDM1YTExYTNiYjA1NTZmYWE5ZGRmYTgyMjc1ZWQ2MGRhNjMwMmY0YzUxM2QzZjEzZTY3NiIsInRhZyI6IiJ9",
            "content": "easdasdasd",
            "likes": 1,
            "comments": 0,
            "tips": 0,
            "is_liked": true,
            "posted_at": "6 days ago",
            "media_allow": true,
            "media": [],
            "preview": []
        }
    ],
    "posts_last": 6
}
```

### Get Recent Unread Notifications
Request Type - GET
```
/notifications/list/{last-Enrypted id: Optional}
```
Response
```
{
    "status": true,
    "unread" : total unread count,
    "notifications":[
        {
            uid: uid,
            text: notification text,
            from: encrypted user Id,
            target: target encrypted Id,
            type: type of notification eg. Post, User, Message, Request
            time: notification Time
        }
    ],
    last: last notification Encrypted Id
}
```
### Mark notification as Read
Request Type - GET
```
/notifications/read/{encNotiId}
```
Reponse
```
{
    'status':true,
    'msg'   : 'Mark as read',
    'unread' : 'Unread Count'
}
```

### Mark All Notifications as Read
Request Type - GET
```
/notifications/read-all
```
Reponse
```
{
    'status':true,
    'msg'   : 'Marked All as read'
}
```

### Delete All Notifications
Request Type - GET
```
/notifications/delete-all
```
Reponse
```
{
    'status':true,
    'msg'   : 'All notifications has been deleted'
}
```

### Notify Seetings
Request type - GET
```
/notify-setting/{0|1}
```
Response
```
{
    'status':true,
    'msg'   : 'Notifications have been enabled/disabled.'
}
```
***Notify Status***
Request Type - GET
```
/notify-status
```
Response
```
{
    notify: 0|1
}
```

### List All Payment Methods
Request type -  GET
```
/payment/methods
```
Response
```
{
    'status':true,
    'msg'   : 'Okay',
    'cards' : [
        {
            uid: uid of method,
            type : VISA/MASTER,
            default: true|false,
            month : Expiry Month,
            year  : Expiry Year,
            last : last 4 digit 
        }
    ]
}
```

### Add new Payment Method
Request Type - POST
```
/payment/add-new
```
Form data
```
{
    meta : {
        fname: Firstname,
        lname: Lastname,
        address: Address,
        city: Address,
        state: Address,
        country: Address,
        zip: zip
    },
    card : Card number,
    cvc : CVV|CVC number,
    month: Expiry Month,
    year: Expiry Year
}
```
Response
```
{
    status: true|false,
    msg : custom,
    card : encCardId
}
```

### Set Card as Default
Request type - GET
```
/payment/default-set/{encCardId}
```
Response
```
{
    status: true,
    msg : 'Selected Payment method is default now',
}
```

### Delete a Card
Request type - GET
```
/payment/delete/{encCardId}
```
Response
```
{
    status: true,
    msg : 'Card has been deleted successfully.',
}
```

### Topup the wallet
Request Type - POST
```
/payment/wallet-topup
```
Form Data
```
{
    amount : Amount to add
    method: selected Card encId
}
```
Response
```
{
    status : true | false
    msg    : Custom message
    balance : Only if success
}
```

### Get User Wallet Balance
Request Type - GET
```
/balance
```
Response
```
{
    balance: balance of wallet
}
```

### Get Payment Transactions 
Request Type - GET
```
/payment/transactions
```
Response
```
{
    "txns": [
        {
            "uid": "eyJpdiI6ImVzT1lzTEIwaER0T0FMckwwN1poMVE9PSIsInZhbHVlIjoibDZUN0NjT0FvOXdwNHpEc2Vya25zUT09IiwibWFjIjoiNTcwNWM1ZjhkMWEyNDY5ODQ4NGRmOTQwYzg1OGE5YmQzMDJkODJhZjI5M2Q1YTA0MDUwNTA4ZDg5ODhlNDdjYSIsInRhZyI6IiJ9",
            "txn_id": "1ae7954b-6b25-4ea7-b917-bad259df2dda",
            "action": "TOPUP",
            "amount": 500,
            "paid": 600,
            "tax": 100,
            "vat": 20,
            "status": "Success",
            "desc": "Wallet topup with £500 at 07:21 AM 17-03-2022",
            "time": "07:21 AM 17-03-2022"
        }
    ]
}
```

### Send Direct Tip
Request Type - POST
```
/tip/direct/{encUserId}
```
Form Data
```
{
    amount: amount,
    message: optional message
}
```
Response
```
{
    status: true | false
    msg  : request status message
}
```

### Send Tip Via Post
Request Type - POST
```
/tip/post/{encPostId}
```
Form Data
```
{
    amount: amount,
    message: optional message
}
```
Response
```
{
    status: true | false
    msg  : request status message
}
```

### Get Wallet History
Request Type - GET
```
/payment/wallet-history
```
Response
{
    'trans' :[
        {
            uid: txn uid,
            type: Type of transaction
            txn_typ : 0|1(Debit|Credit)
            amount:  AMount of txn
            status: Status of txn
            user: Uid of user who have sent or received
            time: Time stamp
        }
    ],
    'last': last enc Id
}

### Update User Name
Request Type - POST
```
/update-username
```
Form Data
```
{
    username: username for update
}
```
Response
```
{
    status : true,
    msg: 'Username has been updated successfully',
    uname: new updated user name
}
```

### Update User Email
Request Type - POST
```
/update-email
```
Form Data
```
{
    email: email for update
}
```
Response
```
{
    status : true,
    msg: 'Email has been updated successfully',
    email: new updated email
}
```

### Change Password
Request Type - POST
```
/update-password
```
Form Data
```
{
    password: new password
    password_confirmation : Confirm password
}
```
Response
```
{
    status : true,
    msg: 'Password has been updated successfully'
}
```

### Get Plan Details to subscribe
Request Type - GET
```
/plan-details/{encPlanId}
```
Response
```
{
    "plan": {
        "title": "Subscription 1",
        "amount": "5.00",
        "benefits": "Hello\nThere\nHow\nAre you",
        "month_2": {
            "off": "20",
            "amount": "4"
        },
        "month_3": null,
        "month_6": null,
        "promotion": {
            "uid": "eyJpdiI6IkhyVnhnQ29xK3BVd1dwek05TWJFSHc9PSIsInZhbHVlIjoiVFJsK09yRy9ZcjJxM1JXVWFWWXNyUT09IiwibWFjIjoiNzdkNzQ0YTU3N2UxZmEwNjI2ZmJhZTIzMDkyMmNlOGY0NWUxYTE2ZjY4YmVhZWYyMjA1ZGQ2MGIyMGVlZTJhNCIsInRhZyI6IiJ9",
            "prom_amount": "3",
            "avail_from": "2022-02-17",
            "avail_to": "2022-03-24"
        }
    }
}
```

### Subscribe to creator Plan
Request type - GET
```
/subscribe/{encPlanId}/{month|optional}
```
Response
```
{
    status: true|false
    wallet: optional | true if enough balance | false if not enough balance
    msg   : Msg about acion
}
```


### Purchase Post Content
Request type - Post
```
/purchase-post/{encPostId}
```
Form Data
```
{
    amount: fix price of post content
}
```
Response
```
{
    status: true|false
    wallet: optional | true if enough balance | false if not enough balance
    msg   : Msg about acion
}
```
### Payout Methoda
Request Type - GET
```
/payout/methods
```
Response
```
{
    "paypal": "email",
    "bank": "Bank Details"
}
```

### Add / Update Paypal Email
Request Type - POST
```
/payout/update-paypal
```
FormData
```
{
    email: "paypal email"
}
```
Response
```
{
    "status": true,
    "msg": "Paypal Updated Successfully."
}
```
### Add / Update Bank Details
Request Type - POST
```
/payout/update-bank
```
FormData
```
{
    bank: "bank details"
}
```
Response
```
{
    "status": true,
    "msg": "Bank Details Updated Successfully."
}
```
***Available Payout***
Request Type - GET
```
/creator/payout/available
```
Response
```
{
    available: amount
}
```

### Get Subscriptions
Request Type -  GET

***Active***
```
/subscription/active
```
***Expired***
```
/subscription/expired
```

### Pro Subscription
***Check Pro Subscription***
Request type - GET
```
/creator/pro-subscription
```

***Subscribe to Pro***
Request Type - POST
```
/creator/pro-subscribe
```
Form Data
```
{
    auto_renew : true | false
}
```

***Update Auto Renew Status***
Request Type - GET
```
/creator/pro-auto-renew/{encSubId}/{status}
```

***Cancel Pro Subs***
Request Type - GET
```
/creator/pro-cancel
```

***Renew Pro Sub***
Request Type - GET
```
/creator/pro-renew/{encSubId}'
```


### Creator Dashboard
***Get All Posts***
Request Type - GET
```
/creator/post/all/{afterEncPostId}
```
Response
```
{
    posts: [
        {
            postdata
        }
    ],
    last
}
```
***Get Active Posts***
```
/creator/post/active/{afterEncPostId}
```
Response
```
{
    posts: [
        {
            postdata
        }
    ],
    last
}
```
***Get blocked Posts***
```
/creator/post/blocked/{afterEncPostId}
```
Response
```
{
    posts: [
        {
            postdata
        }
    ],
    last
}
```
***Get Scheduled Posts***
```
/creator/post/scheduled/{afterEncPostId}
```
Response
```
{
    posts: [
        {
            postdata
        }
    ],
    last
}
```
***Get Active Subscriptions***
Request Type - GET
```
/creator/subscription/active/{after?}
```
***Get Expired Subscriptions***
Request Type - GET
```
/creator/subscription/expired/{after?}
```

***Get Tips Received***
Request Type - GET
```
/creator/tips-received/{after?}
```

***Get Media***
Request Type - GET
```
/creator/media/{image|video}/{after?}
```

***Make A Payout Request***
Request Type - POST
```
/creator/payout/request
```
Form Data
```
{
    amount: amount to withdraw,
    type: paypal|bank
}
```

***Fetch Payout History***
Request Type - GET
```
/creator/payout/history
```
Response
```
{
    transactions : [
        {
            amount : amount to withdraw,
            fee : fee charged by whoyouinto,
            wallet_txn_id: txn id of wallet deducion,
            deduct: amount deducted from wallet
            payout_type: payout method,
            pay_status: request Status,
            time: when requested,
            txn: false | {
                txn_id : transfer txn id,
                payout_details : payout target details,
                amount: amount transfered,
                remark: remark if any,
                time : when processed
            }
        }
    ],
    last: last id
}
```

***Delete A Post***
Request Type - GET
```
/creator/post/delete/{encPostId}
```
Response
```
{
    "status": true|false,
    "msg": "action message"
}
```

***Archive A Post***
Request Type - GET
```
/creator/post/archive/{encPostId}
```
Response
```
{
    "status": true|false,
    "msg": "action message"
}
```

***Get Watermark***
Request Type - GET
```
/creator/watermark
```
Response
```
{
    "watermark": "whoyouinto.com/naveen_t"
}
```

***Update Watermark***
Request Type - POST
```
/creator/watermark
```
Form Data
```
{
    "watermark": "whoyounito.com/username"
}
```
Response
```
{
    "status": true,
    "msg": "Watermark has been updated.",
    "watermak": "whoyouinto.com/naveen_t"
}
```

### Search Creators
Request type - POST
```
/search-creators
```
Form Data
```
{
    search: search keyword
}
```
Response
```
{
    "users": [
        {
            "uid": "eyJpdiI6IkVISWZsdkNDTWhDdDVSUjRKTDNmU0E9PSIsInZhbHVlIjoiU1ZwN1pJUDVjNUJFK1kyK2lRRzN6Zz09IiwibWFjIjoiZGM0ZjhhOWEzYzA5YjdkZDg2MGYyY2Q0YzA2Yzc4MzFjMWViZDlhNWE4YTQzMzNkZTE2MjFhZWUxOWMxZjJkYiIsInRhZyI6IiJ9",
            "name": "Naveen Tehp",
            "username": "naveeeeeeee",
            "role": 1,
            "is_pro": 0,
            "avatar": "default.png",
            "banner": "default.png"
        }
    ]
}
```

### Search in All users - message system
Request type - POST
```
/search-users
```
Form Data
```
{
    search: search keyword
}
```
Response
```
{
    "users": [
        {
            "uid": "eyJpdiI6IkVISWZsdkNDTWhDdDVSUjRKTDNmU0E9PSIsInZhbHVlIjoiU1ZwN1pJUDVjNUJFK1kyK2lRRzN6Zz09IiwibWFjIjoiZGM0ZjhhOWEzYzA5YjdkZDg2MGYyY2Q0YzA2Yzc4MzFjMWViZDlhNWE4YTQzMzNkZTE2MjFhZWUxOWMxZjJkYiIsInRhZyI6IiJ9",
            "name": "Naveen Tehp",
            "username": "naveeeeeeee",
            "role": 1,
            "is_pro": 0,
            "avatar": "default.png",
            "banner": "default.png",
            "paid": true|false|0|1,
            "amount: 0 | more
        }
    ]
}
```

### Get Purchased Media
Request Type - GET
```
/user/purchased-media/{type}/{after?}
```
type => image|video

Response
```
{
    "media":[
        {
            "uid" : uid of image
            "url" : Url to the media
            "added" : Date time when uploaded
        }
    ],
    "type": Type od media requested
    "last": last media encrypted ID
}
```

### Message System
Conversations Will be managed by these APIs

***Send a Message(free)***
Request Type - POST
```
/message/send/{encUserId}
```
Form Data
```
{
    msg: 'Message Data',
    media: file(optional),
    is_locked: 0|1 optional,
    lock_price: price of media(optional)
}
```
Response
```
{
    status: true,
    msg: [
        {
            "uid": "eyJpdiI6Ijg4cHdXS2hZKy9IbnNZUGVkRXk3Y2c9PSIsInZhbHVlIjoiMmRtcEkyZWJOVDhFcld4bWdqUlcxQT09IiwibWFjIjoiZDBjZDdlYzFkOWU0YzExZGU5MjE0OWRmOGI0Yzc1YzIzOWYyMTJlYjU3ODI1NDMyYTQxZTgwYjE5MGQ1NjYxZSIsInRhZyI6IiJ9",
            "text": "Hey there what's up?",
            "reaction": null,
            "sender": true,
            "read_at": "11:53 AM 05-04-2022",
            "time": "12:22 PM 04-04-2022",
            "file":{
                uid: media uid,
                name: name,
                type: image|video,
                url: file url,
                is_locked: true|false,
                lock_price; price if locked
            }
        }
    ]
}
```

***Get Conversations***
Request Type - GET
```
/message/conversations
```
Response
```
{
    consversations:[
        {
            uid: Uid of conversation,
            user: {
                user data
            },
            msg: {
                recent message data
            },
            time: time of last message
        }
    ]
}
```

***Load Chat messages***
Request Type - GET
```
/message/chat/{encUserId}
```
Response
```
{
    "msgs": [
        {
            "uid": "eyJpdiI6Ijg4cHdXS2hZKy9IbnNZUGVkRXk3Y2c9PSIsInZhbHVlIjoiMmRtcEkyZWJOVDhFcld4bWdqUlcxQT09IiwibWFjIjoiZDBjZDdlYzFkOWU0YzExZGU5MjE0OWRmOGI0Yzc1YzIzOWYyMTJlYjU3ODI1NDMyYTQxZTgwYjE5MGQ1NjYxZSIsInRhZyI6IiJ9",
            "text": "Hey there what's up?",
            "reaction": null,
            "sender": true,
            "read_at": "11:53 AM 05-04-2022",
            "time": "12:22 PM 04-04-2022"
        },
        {
            "uid": "eyJpdiI6IkZVMlVzT1RIbzBzUW15THZTOURHZkE9PSIsInZhbHVlIjoieHI0WkhvK1lZZ3lScjNpUk5wWWpRUT09IiwibWFjIjoiNzlkZWRlMzdmMmE3MjljNmZlYmQ3NzkwODE5N2EzNDg1ZGI3MGE3ZTU3ZDUyMzdlYTE0OTEwNzg5OWM5YWM4ZSIsInRhZyI6IiJ9",
            "text": "Are you there?",
            "reaction": null,
            "sender": true,
            "read_at": "03:53 AM 05-04-2022",
            "time": "12:24 PM 04-04-2022"
        },
        {
            "uid": "eyJpdiI6IlpKV2xrRDNCODh1Vkd2NzJtWXB6QWc9PSIsInZhbHVlIjoiWlhCRnM2ZVhJb2VBMXFibWFqbVFjUT09IiwibWFjIjoiOWM2ZTRmMDJjZGMxMTQzY2JhZmE0MTI3OTE1MTg0ODU0NWViMjdiY2Q5M2IwYjk5MGRiZmYwZjIyZmMwNTg4NSIsInRhZyI6IiJ9",
            "text": "Hey, nice to hear from you after a long time. I'm fine your day.",
            "reaction": null,
            "sender": false,
            "read_at": false,
            "time": "12:25 PM 04-04-2022"
        }
    ],
    "last": "eyJpdiI6ImNoVFp6U1J1WjdEWDc4ZG5JSjN2NVE9PSIsInZhbHVlIjoiVFJNODdTYWJFM2c0L1BnTmtXQmhnUT09IiwibWFjIjoiMjg2ZTA1MjVkZWYxZDdkYjU3ODY2NGFkOTcxOWY3YjMzN2JmYjA3Nzk0ZTYzOTkxY2ZjMjVhM2ZlZDZmMmQ4OCIsInRhZyI6IiJ9",
    "more": "eyJpdiI6Ik5rdWd2cEJTem9KOFlyOVNsSXV6U2c9PSIsInZhbHVlIjoiOGpRWlRPdHoxRlJSR2llY2wrNFNFdz09IiwibWFjIjoiMTVkZjEzOTQzZTI1MjJmMzNjNTZmYTk4ZjI0NDk2MzRhYmUyODhjMTk2YjhhYTFjMWYyZjU4NDY1YmIyODJlZSIsInRhZyI6IiJ9"
}
```

***Load more within chat***
Request Type - GET
```
/message/load-more/{encConversationId}/{encMoreId}
```
Response
```
{
    "msgs": [
        {
            "uid": "eyJpdiI6Ijg4cHdXS2hZKy9IbnNZUGVkRXk3Y2c9PSIsInZhbHVlIjoiMmRtcEkyZWJOVDhFcld4bWdqUlcxQT09IiwibWFjIjoiZDBjZDdlYzFkOWU0YzExZGU5MjE0OWRmOGI0Yzc1YzIzOWYyMTJlYjU3ODI1NDMyYTQxZTgwYjE5MGQ1NjYxZSIsInRhZyI6IiJ9",
            "text": "Hey there what's up?",
            "reaction": null,
            "sender": true,
            "read_at": "11:53 AM 05-04-2022",
            "time": "12:22 PM 04-04-2022"
        },
        {
            "uid": "eyJpdiI6IkZVMlVzT1RIbzBzUW15THZTOURHZkE9PSIsInZhbHVlIjoieHI0WkhvK1lZZ3lScjNpUk5wWWpRUT09IiwibWFjIjoiNzlkZWRlMzdmMmE3MjljNmZlYmQ3NzkwODE5N2EzNDg1ZGI3MGE3ZTU3ZDUyMzdlYTE0OTEwNzg5OWM5YWM4ZSIsInRhZyI6IiJ9",
            "text": "Are you there?",
            "reaction": null,
            "sender": true,
            "read_at": "03:53 AM 05-04-2022",
            "time": "12:24 PM 04-04-2022"
        },
        {
            "uid": "eyJpdiI6IlpKV2xrRDNCODh1Vkd2NzJtWXB6QWc9PSIsInZhbHVlIjoiWlhCRnM2ZVhJb2VBMXFibWFqbVFjUT09IiwibWFjIjoiOWM2ZTRmMDJjZGMxMTQzY2JhZmE0MTI3OTE1MTg0ODU0NWViMjdiY2Q5M2IwYjk5MGRiZmYwZjIyZmMwNTg4NSIsInRhZyI6IiJ9",
            "text": "Hey, nice to hear from you after a long time. I'm fine your day.",
            "reaction": null,
            "sender": false,
            "read_at": false,
            "time": "12:25 PM 04-04-2022"
        }
    ],
    "more": "eyJpdiI6Ik5rdWd2cEJTem9KOFlyOVNsSXV6U2c9PSIsInZhbHVlIjoiOGpRWlRPdHoxRlJSR2llY2wrNFNFdz09IiwibWFjIjoiMTVkZjEzOTQzZTI1MjJmMzNjNTZmYTk4ZjI0NDk2MzRhYmUyODhjMTk2YjhhYTFjMWYyZjU4NDY1YmIyODJlZSIsInRhZyI6IiJ9"
}
```

***Get Recent Message while in chat window***
Request Type - GET
```
/message/recent/{encConversationId}/{encLastId|false}
```
Response
```
{
    "msgs": [
        {
            "uid": "eyJpdiI6Ijg4cHdXS2hZKy9IbnNZUGVkRXk3Y2c9PSIsInZhbHVlIjoiMmRtcEkyZWJOVDhFcld4bWdqUlcxQT09IiwibWFjIjoiZDBjZDdlYzFkOWU0YzExZGU5MjE0OWRmOGI0Yzc1YzIzOWYyMTJlYjU3ODI1NDMyYTQxZTgwYjE5MGQ1NjYxZSIsInRhZyI6IiJ9",
            "text": "Hey there what's up?",
            "reaction": null,
            "sender": true,
            "read_at": "11:53 AM 05-04-2022",
            "time": "12:22 PM 04-04-2022"
        },
        {
            "uid": "eyJpdiI6IkZVMlVzT1RIbzBzUW15THZTOURHZkE9PSIsInZhbHVlIjoieHI0WkhvK1lZZ3lScjNpUk5wWWpRUT09IiwibWFjIjoiNzlkZWRlMzdmMmE3MjljNmZlYmQ3NzkwODE5N2EzNDg1ZGI3MGE3ZTU3ZDUyMzdlYTE0OTEwNzg5OWM5YWM4ZSIsInRhZyI6IiJ9",
            "text": "Are you there?",
            "reaction": null,
            "sender": true,
            "read_at": "03:53 AM 05-04-2022",
            "time": "12:24 PM 04-04-2022"
        },
        {
            "uid": "eyJpdiI6IlpKV2xrRDNCODh1Vkd2NzJtWXB6QWc9PSIsInZhbHVlIjoiWlhCRnM2ZVhJb2VBMXFibWFqbVFjUT09IiwibWFjIjoiOWM2ZTRmMDJjZGMxMTQzY2JhZmE0MTI3OTE1MTg0ODU0NWViMjdiY2Q5M2IwYjk5MGRiZmYwZjIyZmMwNTg4NSIsInRhZyI6IiJ9",
            "text": "Hey, nice to hear from you after a long time. I'm fine your day.",
            "reaction": null,
            "sender": false,
            "read_at": false,
            "time": "12:25 PM 04-04-2022"
        }
    ],
    "last": "eyJpdiI6ImNoVFp6U1J1WjdEWDc4ZG5JSjN2NVE9PSIsInZhbHVlIjoiVFJNODdTYWJFM2c0L1BnTmtXQmhnUT09IiwibWFjIjoiMjg2ZTA1MjVkZWYxZDdkYjU3ODY2NGFkOTcxOWY3YjMzN2JmYjA3Nzk0ZTYzOTkxY2ZjMjVhM2ZlZDZmMmQ4OCIsInRhZyI6IiJ9"
}
```

***Check Read Status of the Message***
Request Type - GET
```
/message/read-status/{encMsgId}
```
Response
```
{
    status: false | datetime on which read
}
```

***Get Unread Message Count***
Request Type - GET
```
/message/unread-count
```
Response
```
{
    unread: 0
}
```

***Get Creator Message Settings***
Request Type - GET
```
/creator/message/settings
```
Response
```
{
    "status": true,
    "settings": {
        "msg_setting": 1,
        "amount": null
    }
}
```

***Update Creator Message Settings***
Request Type - POST
```
/creator/message/settings
```
Form Data
```
{
    "msg_setting" : 0|1,
    "amount" : int ammount if paid
}
```
Response
```
{
    "status": true,
    "msg": "Message settings have been updated.",
    "settings": {
        "msg_setting": "1",
        "amount": "2"
    }
}
```

***Purchase a Message Media***
Request Type - GET
```
/message/purchase/{encMsgId}
```
Response
```
{
    status: true|false,
    wallet: true|false,
    msg: process message,
    file: {
        file data
    }(optional)
}
```

### Yoti Age Verification ###
***Check is Age Verified***
Request Type - GET
```
/is-verified
```
Response
```
{
    'status': false,
    'age'   : false,
    'msg'   : 'Please verify your age first!'
}
```

***Send Yoti Token To verify Age***
Request Type - POST
```
/age-verify
```
Form Data
```
{
    "token": "yoti token"
}
```
Response
```
{
    'status': true,
    'age'   : true,
    'msg'   : 'Age verified.'
}
```
"# fansly-database" 
