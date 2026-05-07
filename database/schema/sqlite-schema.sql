CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "phone" varchar not null,
  "password" varchar not null,
  "role" varchar check("role" in('user', 'admin')) not null default 'user',
  "blocked" tinyint(1) not null default '0',
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_phone_unique" on "users"("phone");
CREATE TABLE IF NOT EXISTS "vehicles"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "category" varchar not null,
  "price_per_day" integer not null,
  "seats" integer not null default '5',
  "transmission" varchar check("transmission" in('Manuelle', 'Automatique')) not null default 'Manuelle',
  "ac" tinyint(1) not null default '1',
  "image" varchar,
  "available" tinyint(1) not null default '1',
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "reservations"(
  "id" integer primary key autoincrement not null,
  "reservation_number" varchar not null,
  "user_id" integer not null,
  "vehicle_id" integer not null,
  "start_date" date not null,
  "end_date" date not null,
  "total_price" integer not null,
  "acompte" integer not null,
  "status" varchar check("status" in('pending', 'confirmed', 'rejected', 'completed')) not null default 'pending',
  "admin_note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("vehicle_id") references "vehicles"("id") on delete cascade
);
CREATE INDEX "reservations_vehicle_id_start_date_end_date_status_index" on "reservations"(
  "vehicle_id",
  "start_date",
  "end_date",
  "status"
);
CREATE UNIQUE INDEX "reservations_reservation_number_unique" on "reservations"(
  "reservation_number"
);
CREATE TABLE IF NOT EXISTS "comments"(
  "id" integer primary key autoincrement not null,
  "vehicle_id" integer not null,
  "user_id" integer not null,
  "rating" integer not null,
  "body" text not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("vehicle_id") references "vehicles"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE UNIQUE INDEX "comments_vehicle_id_user_id_unique" on "comments"(
  "vehicle_id",
  "user_id"
);
CREATE TABLE IF NOT EXISTS "trackings"(
  "id" integer primary key autoincrement not null,
  "vehicle_id" integer not null,
  "latitude" numeric not null,
  "longitude" numeric not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("vehicle_id") references "vehicles"("id") on delete cascade
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_sessions_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2024_01_01_000001_create_users_table',1);
INSERT INTO migrations VALUES(5,'2024_01_01_000002_create_vehicles_table',1);
INSERT INTO migrations VALUES(6,'2024_01_01_000003_create_reservations_table',1);
INSERT INTO migrations VALUES(7,'2024_01_01_000004_create_comments_table',1);
INSERT INTO migrations VALUES(8,'2026_04_22_231357_create_trackings_table',2);
