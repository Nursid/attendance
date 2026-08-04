# OBHS Feedback System - API Reference

Mobile APIs for the OBHS (On Board Housekeeping Service) Feedback System, implemented in
`application/controllers/Api_v20.php` and backed by `application/models/Obhs_Model.php`.

## Conventions

- **Base URL:** `http://localhost/attendence/index.php/Api_v20` (replace host with your server).
  If URL rewriting is enabled you may drop `/index.php`.
- **Method:** All endpoints are `POST`.
- **Content-Type:** `application/json`.
- **Envelope:** Every request and response body is wrapped in a single `checkon` object.
- **Auth:** Identity is the caller's registered `mobile` (looked up in the `login` table).
  - `user_group = 2` -> staff / janitor (submits feedback, sees own records).
  - `user_group = 1` -> business admin (lists all company records, updates, exports).
- **PSI:** The server always recalculates the PSI score: `PSI = (total score / 12) x 100`.
  Very Good scores 4, Good 3, Poor 2, and **Not Attended scores 0** (it does not contribute,
  but the denominator stays 12). Any `psi_score` sent by the client is ignored.
  Example: 4 + 3 + 2 + 0 = 9 => (9/12) x 100 = **75.00**.
- **Auto complaint:** When `feedback_type = "Complaint"`, a row is also inserted into the existing
  `complain` table (category `OBHS`) and linked via `obhs_feedback.complaint_id`.

## Rating fields (all required, only the 4 values below are accepted)

| Field | Meaning |
|-------|---------|
| `rating_toilet_cleaning` | Cleaning of Toilet |
| `rating_compartment_cleaning` | Cleaning of Compartment |
| `rating_toiletries_availability` | Availability of Toiletries |
| `rating_behaviour` | Behaviour |

| Value | Label | PSI score contribution |
|-------|-------|------------------------|
| `4` | Very Good | 4 |
| `3` | Good | 3 |
| `2` | Poor | 2 |
| `1` | Not Attended | 0 |

Any other value (including a missing field) is rejected with
`{"checkon":{"msg":"rating_... must be one of 4 (Very Good), 3 (Good), 2 (Poor), 1 (Not Attended)","status":"0"}}`.

---

## 1. Save Feedback

`POST /Api_v20/addObhsFeedback`

Saves a feedback/complaint record. Required: `mobile`, `train_no`, `coach_no`, `journey_date`,
`passenger_name` and all four rating fields.

**Option A - JSON body** (photo as optional base64 string):

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/addObhsFeedback" \
  -H "Content-Type: application/json" \
  -d '{
    "checkon": {
      "mobile": "9876543210",
      "train_no": "12951",
      "train_name": "Mumbai Rajdhani",
      "coach_no": "B4",
      "journey_date": "2026-07-19",
      "boarding_station": "NDLS",
      "destination_station": "BCT",
      "pnr_no": "1234567890",
      "seat_no": "45",
      "passenger_name": "Rahul Sharma",
      "passenger_mobile": "9999999999",
      "passenger_email": "rahul@example.com",
      "rating_toilet_cleaning": 4,
      "rating_compartment_cleaning": 3,
      "rating_toiletries_availability": 2,
      "rating_behaviour": 1,
      "feedback_type": "Feedback",
      "remarks": "Coach was clean and staff polite",
      "janitor_name": "Suresh Kumar",
      "latitude": "28.6139",
      "longitude": "77.2090",
      "location": "New Delhi",
      "photo": ""
    }
  }'
```

**Option B - multipart/form-data** (real image file upload, fields sent flat):

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/addObhsFeedback" \
  -F "mobile=9876543210" \
  -F "train_no=12951" \
  -F "train_name=Mumbai Rajdhani" \
  -F "coach_no=B4" \
  -F "journey_date=2026-07-19" \
  -F "passenger_name=Rahul Sharma" \
  -F "rating_toilet_cleaning=4" \
  -F "rating_compartment_cleaning=3" \
  -F "rating_toiletries_availability=2" \
  -F "rating_behaviour=1" \
  -F "feedback_type=Feedback" \
  -F "remarks=Coach was clean" \
  -F "photo=@image.jpg"
```

(In Postman: Body -> form-data, add the fields above and set `photo` to type File.
A `checkon` form field holding the whole JSON payload string also works alongside the file.)

**Response** (photo is returned as a full URL when uploaded)

```json
{"checkon":{"msg":"Feedback Saved Successfully","status":"1","feedback_id":1,"psi_score":75,"complaint_id":0,"photo":"http://.../upload/obhs/1754212345_1234.jpg"}}
```

For a complaint, set `"feedback_type": "Complaint"`; the response `complaint_id` will be the
auto-created `complain` row id.

---

## 2. List / Search / Filter Feedback

`POST /Api_v20/getObhsFeedbackList`

Staff (`user_group 2`) get their own submissions. Business (`user_group 1`) get the whole
company with all filters. Every field except `mobile` is optional.

**Basic request (no filters — returns all feedback for the business):**

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/getObhsFeedbackList" \
  -H "Content-Type: application/json" \
  -d '{
    "checkon": {
      "mobile": "9876543210",
      "page": 1,
      "limit": 20
    }
  }'
```

**With filters.** All filters are combined with AND — a record must match every filter you
send, so only include the ones you actually want. For example `"feedback_type": "Complaint"`
will hide all records saved as `"Feedback"`, and `"psi_max": 60` will hide records with a
higher PSI score:

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/getObhsFeedbackList" \
  -H "Content-Type: application/json" \
  -d '{
    "checkon": {
      "mobile": "9876543210",
      "page": 1,
      "limit": 20,
      "search": "12951",
      "start_date": "2026-07-01",
      "end_date": "2026-07-31",
      "train_no": "12951",
      "coach_no": "B4",
      "feedback_type": "Complaint",
      "status": "Pending",
      "psi_min": 0,
      "psi_max": 60,
      "sort_by": "psi_score",
      "sort_dir": "ASC"
    }
  }'
```

**Response**

```json
{"checkon":{"status":"1","total":3,"page":1,"limit":20,"list":[ { "id":"3", "train_no":"12951", "psi_score":"30.00", "...": "..." } ]}}
```

Sort options (`sort_by`): `id`, `journey_date`, `train_no`, `coach_no`, `passenger_name`,
`psi_score`, `feedback_type`, `status`, `date_time`. `sort_dir`: `ASC` or `DESC`.

---

## 3. View Single Feedback

`POST /Api_v20/getObhsFeedbackDetail`

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/getObhsFeedbackDetail" \
  -H "Content-Type: application/json" \
  -d '{"checkon":{"mobile":"9876543210","id":1}}'
```

**Response** - the feedback object contains only the 4 ratings, PSI, remarks and photo:

```json
{"checkon":{"status":"1","feedback":{
  "rating_toilet_cleaning": 4,
  "rating_compartment_cleaning": 3,
  "rating_toiletries_availability": 2,
  "rating_behaviour": 1,
  "psi_score": "75.00",
  "remarks": "Coach was clean and staff polite",
  "photo": "http://.../upload/obhs/1754212345_1234.jpg"
}}}
```

`photo` is returned as a full URL ('' when none). Staff can only view their own records.

---

## 4. Update Feedback (business only)

`POST /Api_v20/updateObhsFeedback`

Updates `status` and/or `remarks`. If the record has a linked complaint, its status is synced.
Valid statuses: `Pending`, `Working`, `Done`.

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/updateObhsFeedback" \
  -H "Content-Type: application/json" \
  -d '{"checkon":{"mobile":"9876543210","id":1,"status":"Done","remarks":"Resolved on spot"}}'
```

**Response**

```json
{"checkon":{"msg":"Feedback Updated Successfully","status":"1"}}
```

---

## 5. Export Feedback to Excel (business only)

`POST /Api_v20/exportObhsFeedback`

Accepts the same filters as the list API and streams an `.xls` download.

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/exportObhsFeedback" \
  -H "Content-Type: application/json" \
  -d '{"checkon":{"mobile":"9876543210","start_date":"2026-07-01","end_date":"2026-07-31"}}' \
  -o OBHS_Feedback.xls
```

In Postman use **Send and Download** so the file is saved.

---

## 6. Train Master List (train no + name + coach position)

`POST /Api_v20/getObhsTrainList`

Returns the global train master (`obhs_train_master`) - same list for every business.
Each train carries **both direction numbers** of the pair: the source sheet lists
`12155/56`, which is stored as `train_no = 12155` and `train_no_return = 12156`.
`coach_position` is the rake order as printed on the sheet; `coaches` is the same list
already split into an array. Optional `search` matches train number, name or coach code.

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/getObhsTrainList" \
  -H "Content-Type: application/json" \
  -d '{"checkon":{"mobile":"9876543210","search":"humsafar"}}'
```

**Response**

```json
{"checkon":{"status":"1","total":1,"list":[{
  "id":"6",
  "train_no":"22169",
  "train_no_return":"22170",
  "train_no_pair":"22169/22170",
  "train_name":"Santragachhi Humsafar Express",
  "coach_position":"B1,B2,B3,B4,B5,B6,B7,B8,B9,B10,B11,B12,B13,B14,S1,S2,S3,S4,S5,S6",
  "total_coaches":20,
  "coaches":["B1","B2","B3","B4","B5","B6","B7","B8","B9","B10","B11","B12","B13","B14","S1","S2","S3","S4","S5","S6"]
}]}}
```

Use this to populate the train picker in the app; drop `search` to get all trains.

---

## 7. Coaches of One Train

`POST /Api_v20/getObhsTrainCoaches`

Resolves a single train from **either** direction number - `12155` and `12156` both
return Bhopal Express - and returns its coaches in rake order. Use it to fill the coach
dropdown once the janitor has picked a train.

```bash
curl -X POST "http://localhost/attendence/index.php/Api_v20/getObhsTrainCoaches" \
  -H "Content-Type: application/json" \
  -d '{"checkon":{"mobile":"9876543210","train_no":"12156"}}'
```

**Response**

```json
{"checkon":{"status":"1","id":"1","train_no":"12155","train_no_return":"12156",
  "train_no_pair":"12155/12156","train_name":"Bhopal Express",
  "coach_position":"H1,A1,A2,B1,B2,B3,B4,M1,S1,S2,S3,S4,S5,S6,S7","total_coaches":15,
  "coaches":["H1","A1","A2","B1","B2","B3","B4","M1","S1","S2","S3","S4","S5","S6","S7"]}}
```

An unknown train number returns `{"checkon":{"msg":"No Data Found","status":"0","coaches":[]}}`.

---

## Train master table

`obhs_train_master` is created and seeded by `obhs_train_master.sql` (14 trains).
It is a global list - not scoped by `bid` - and rows with `status = 0` are hidden
from both APIs and the portal filters.

| Column | Notes |
|--------|-------|
| `train_no` | up direction number, unique |
| `train_no_return` | down/return direction number |
| `train_name` | e.g. `Bhopal Express` |
| `coach_position` | comma separated coach codes in rake order |
| `total_coaches` | derived from `coach_position` |
| `status` | `1` active, `0` disabled |

Feedback rows store a single `train_no`; the portal and APIs match it against either
column, so a record saved as `12156` still shows the Bhopal Express name and rake.

---

## Error responses

| Situation | Response |
|-----------|----------|
| Unknown mobile / wrong user_group | `{"checkon":{"msg":"Unauthorized","status":"0"}}` |
| Missing required save fields | `{"checkon":{"msg":"train_no, coach_no, journey_date and passenger_name are required","status":"0"}}` |
| Coach lookup without a train | `{"checkon":{"msg":"train_no is required","status":"0"}}` |
| Detail/record not found | `{"checkon":{"msg":"No Data Found","status":"0"}}` |
| Update with no valid fields | `{"checkon":{"msg":"Nothing to Update","status":"0"}}` |

---

## Admin Reporting Dashboard (web portal, not mobile API)

Session-authenticated pages under the `Obhs` controller (sidebar menu "OBHS Feedback"):

| Page | Route |
|------|-------|
| Dashboard (cards + charts) | `obhs-dashboard` |
| Master Search | `obhs-master-search` |
| Train Wise Report | `obhs-train-report` |
| Coach Wise Report | `obhs-coach-report` |
| Janitor Performance | `obhs-janitor-report` |
| PSI Report | `obhs-psi-report` |
| Monthly Report | `obhs-monthly-report` |
| Complaint Tracking | `obhs-complaints` |
| Feedback detail | `obhs-feedback/{id}` |
| Excel export | `obhs-export/{report}` |

Each report supports filtering, search, pagination, sorting, Excel export, PDF export, and print.
The Train and Coach filters are driven by `obhs_train_master`, so picking a train narrows the
coach dropdown to that train's rake; train numbers seen only in old feedback rows are still listed.
A `web_login` account of type `A` (super admin) sees all businesses; types `B`/`P` are scoped to
their own company.
