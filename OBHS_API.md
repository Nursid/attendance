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
- **PSI:** The server always recalculates the PSI score (average of the rated 1-5 categories x 20 => 0-100).
  Any `psi_score` sent by the client is ignored.
- **Auto complaint:** When `feedback_type = "Complaint"`, a row is also inserted into the existing
  `complain` table (category `OBHS`) and linked via `obhs_feedback.complaint_id`.

## Rating fields (all optional, 1-5 scale, 0 or omitted = not rated)

| Field | Meaning |
|-------|---------|
| `rating_coach_cleanliness` | Coach Cleanliness |
| `rating_toilet_cleanliness` | Toilet Cleanliness |
| `rating_doorway_cleanliness` | Doorway Cleanliness |
| `rating_bedroll` | Bedroll Quality |
| `rating_staff_behaviour` | Staff Behaviour |
| `rating_pest_control` | Pest Control |

---

## 1. Save Feedback

`POST /Api_v20/addObhsFeedback`

Saves a feedback/complaint record. Required: `mobile`, `train_no`, `coach_no`, `journey_date`,
`passenger_name`. `photo` is an optional base64 JPEG string (saved to `upload/obhs/`).

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
      "rating_coach_cleanliness": 5,
      "rating_toilet_cleanliness": 4,
      "rating_doorway_cleanliness": 4,
      "rating_bedroll": 3,
      "rating_staff_behaviour": 5,
      "rating_pest_control": 4,
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

**Response**

```json
{"checkon":{"msg":"Feedback Saved Successfully","status":"1","feedback_id":1,"psi_score":84,"complaint_id":0}}
```

For a complaint, set `"feedback_type": "Complaint"`; the response `complaint_id` will be the
auto-created `complain` row id.

---

## 2. List / Search / Filter Feedback

`POST /Api_v20/getObhsFeedbackList`

Staff (`user_group 2`) get their own submissions. Business (`user_group 1`) get the whole
company with all filters. Every field except `mobile` is optional.

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

**Response**

```json
{"checkon":{"status":"1","feedback":{ "id":"1", "train_no":"12951", "photo":"http://.../upload/obhs/....jpg", "...": "..." }}}
```

`photo` is returned as a full URL. Staff can only view their own records.

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

## Error responses

| Situation | Response |
|-----------|----------|
| Unknown mobile / wrong user_group | `{"checkon":{"msg":"Unauthorized","status":"0"}}` |
| Missing required save fields | `{"checkon":{"msg":"train_no, coach_no, journey_date and passenger_name are required","status":"0"}}` |
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
A `web_login` account of type `A` (super admin) sees all businesses; types `B`/`P` are scoped to
their own company.
