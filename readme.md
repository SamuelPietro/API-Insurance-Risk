
# API Insurance Risk

![](https://img.shields.io/github/stars/SamuelPietro/API-Insurance-Risk) ![](https://img.shields.io/github/forks/SamuelPietro/API-Insurance-Risk) ![](https://img.shields.io/github/languages/top/SamuelPietro/API-Insurance-Risk) ![](https://img.shields.io/github/release/SamuelPietro/API-Insurance-Risk) ![](https://img.shields.io/github/issues/SamuelPietro/API-Insurance-Risk) ![](https://img.shields.io/github/repo-size/SamuelPietro/API-Insurance-Risk) 


We determine a user's insurance needs by asking personal and risk-related questions and collecting user information. From this data, we determine a risk score for each line of insurance and then suggest an insurance plan ("economic", "regular", "responsible") corresponding to your risk score.

## Running

To run the api on your PHP server, just save the files in the root folder (usually "public_html).
To call API you must enter your server URL plus 
***`/?action=data&json={your json payload}`***

for example: 
***`https://sshost.com.br/apis/API-Insurance-Risk/?action=data&json={"age":35,"dependents":2,"house":{"ownership_status":"owned"},"income":0,"marital_status":"married","risk_questions":[0,1,0],"vehicle":{"year":2018}}`***


Another possible use is to use POST sending, for this we just send our json payload using the POST method to
***`https://sshost.com.br/apis/API-Insurance-Risk/?action=data`***

### Tests and environment
There is a post.php file inside the root directory that generates a form for inserting the json payload. This form can be accessed by calling it directly in the browser.
***`https://sshost.com.br/apis/API-Insurance-Risk/post.php`***

## The input
The application receives the JSON payload through the API endpoint and transforms it into a risk profile by calculating a risk score for each line of insurance (life, disability, home & auto) based on the information provided by the user, like this example:

```json
{
  "age": 35,
  "dependents": 2,
  "house": {"ownership_status": "owned"},
  "income": 0,
  "marital_status": "married",
  "risk_questions": [0, 1, 0],
  "vehicle": {"year": 2018}
}
```
## The risk algorithm
First, it calculates the base score by summing the answers from the risk questions, resulting in a number ranging from 0 to 3. Then, it applies the following rules to determine a risk score for each line of insurance.

If the user doesn’t have income, vehicles or houses, she is ineligible for disability, auto, and home insurance, respectively.

1. If the user is over 60 years old, she is ineligible for disability and life insurance.
2. If the user is under 30 years old, deduct 2 risk points from all lines of insurance. If she is between 30 and 40 years old, deduct 1.
3. If her income is above $200k, deduct 1 risk point from all lines of insurance.
4. If the user's house is mortgaged, add 1 risk point to her home score and add 1 risk point to her disability score.
5. If the user has dependents, add 1 risk point to both the disability and life scores.
6. If the user is married, add 1 risk point to the life score and remove 1 risk point from disability.
7. If the user's vehicle was produced in the last 5 years, add 1 risk point to that vehicle’s score.

This algorithm results in a final score for each line of insurance, which should be processed using the following ranges:
- **0 and below** maps to **“economic”**.
- **1 and 2** maps to **“regular”**.
- **3 and above** maps to **“responsible”**.

## The output
Considering the data provided above, the application should return the following JSON payload:

```json
{
    "auto": "regular",
    "disability": "ineligible",
    "home": "economic",
    "life": "regular"
}
```

