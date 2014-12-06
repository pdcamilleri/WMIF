use wmifdatabase;


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
CREATE TABLE simultaneous_array (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  idx INT NOT NULL,
  product INT NOT NULL, -- the product that this value corresponds to, 1 meaning the 1st product on the admin page, 2 meaning the 2nd.
                        -- to know if this product was the 1st or 2nd that was shown to the user, consult the 
  value FLOAT NOT NULL,
  PRIMARY KEY (id, problem_id, product, idx),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);

CREATE TABLE experience_array (
  id INT NOT NULL,
  problem_id INT NOT NULL,
  idx INT NOT NULL,
  value FLOAT NOT NULL,
  PRIMARY KEY (id, problem_id, idx),
  FOREIGN KEY (id, problem_id) REFERENCES choices(id, problem_id)
);




