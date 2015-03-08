use wmifdatabase;

-- TODO remove comments and add to documentation page on the github wiki

-- holds all the information relating to the participant of this experiment.
CREATE TABLE demographics (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- id created internally, no meaning beyond the database
  mid VARCHAR(30),
  male INT,
  age INT,
  education INT,
  employment INT,
  marital INT,
  income INT
);

-- the key choices made by the participant at the end of the experiment
CREATE TABLE choices (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  chose_risky INT,
  choice_strength INT,
  friend_recommendation INT,
  PRIMARY KEY (id, problem_id), -- a problem is a choice between two or more products.
  FOREIGN KEY (id) REFERENCES demographics(id)
);

-- the values that appeared in the simultaneous condition
CREATE TABLE simultaneous_values (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL, -- the product that this value corresponds to, 1 meaning the 1st product on the admin page, 2 meaning the 2nd.
                        -- to know if this product was the 1st or 2nd that was shown to the user, consult the ...
  idx INT NOT NULL,
  value FLOAT NOT NULL,
  PRIMARY KEY (id, problem_id, optn, idx),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);

-- the values that were displayed in the experience condition
CREATE TABLE experience_values (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL, -- pretty sure we need a product here too
  idx INT NOT NULL,
  value FLOAT NOT NULL,
  PRIMARY KEY (id, problem_id, optn, idx),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);

-- the original values that were used to produce the information formats
CREATE TABLE original_values (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL, -- pretty sure we need a product here
  idx INT NOT NULL,
  value FLOAT NOT NULL,
  PRIMARY KEY (id, problem_id, optn, idx),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);

-- the three things the participant entered on the confidence interval page
CREATE TABLE confidence_interval (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL,
  lower INT NOT NULL,
  best INT NOT NULL,
  upper INT NOT NULL,
  PRIMARY KEY (id, problem_id, optn),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);
  
-- the outcomes, from 1 - 10, that they entered using the slider bars
CREATE TABLE slider_outcomes (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL,
  idx INT NOT NULL,
  value INT NOT NULL,
  PRIMARY KEY (id, problem_id, optn, idx),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);

-- 1 or 0 depending on if the formats were shown to the user
CREATE TABLE formats_shown (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL,
  average_shown INT NOT NULL,
  description_shown INT NOT NULL,
  description_random INT NOT NULL,
  frequency_shown INT NOT NULL,
  frequency_random INT NOT NULL,
  distribution_shown INT NOT NULL,
  distribution_random INT NOT NULL,
  wordcloud_shown INT NOT NULL,
  simultaneous_shown INT NOT NULL,
  simultaneous_random INT NOT NULL,
  experience_shown INT NOT NULL,
  experience_random INT NOT NULL,
  PRIMARY KEY (id, problem_id, optn),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);
 
-- number of samples
CREATE TABLE samples (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  optn INT NOT NULL,
  num_samples INT NOT NULL,
  PRIMARY KEY (id, problem_id, optn),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);














