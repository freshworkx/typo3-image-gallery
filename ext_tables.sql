CREATE TABLE sys_file_collection (
  bm_image_gallery_description text,
  bm_image_gallery_path_segment varchar(255) DEFAULT '' NOT NULL,
  bm_image_gallery_location varchar(255) DEFAULT '' NOT NULL,
  bm_image_gallery_date int(11) DEFAULT 0 NOT NULL,
);
