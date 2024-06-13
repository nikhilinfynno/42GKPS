<!DOCTYPE html>
<html>

<head>
	<title>User Biodata PDF</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			width: 100%;
			padding: 0px;
			margin: 0px;
		}
		.header,
		.footer {
			text-align: center;
			padding: 10px 0px;
			background-color: #f1f1f1;
			border-radius: 10px;
		}

		.avatar {
			text-align: center;
			margin: 20px 0;
		}

		.avatar img {
			/* border-radius: 50%; */
		}

		.section {
			margin: 20px 0;
		}

		.section h3 {
			background-color: #f19f30;
			color: white;
			padding: 10px;
			border-radius: 5px;
		}

		.details p {
			margin: 5px 0;
			line-height: 1.6;
		}

		.details p strong {
			display: inline-block;
			width: 150px;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="header">
			<h2>Member Details</h2>
		</div>

		<div class="avatar">
			<img src="{{ $data['avatar_url'] }}" alt="Avatar" width="100" height="100">
		</div>

		<div class="section">
			<h3>Personal Information</h3>
			<div class="details">
				<p><strong>Full Name:</strong> {{ $data['full_name'] }}</p>
				<p><strong>Date of Birth:</strong> {{ $data['dob'] }}</p>
				<p><strong>Gender:</strong> {{ $data['gender'] }}</p>
				<p><strong>Age:</strong> {{ $data['age'] }}</p>
				<p><strong>Native Village:</strong> {{ $data['native_village'] }}</p>
				<p><strong>Blood Group:</strong> {{ $data['blood_group'] }}</p>
			</div>
		</div>

		<div class="section">
			<h3>Education</h3>
			<div class="details">
				<p><strong>Education:</strong> {{ $data['education'] }}</p>
				<p><strong>Education Detail:</strong> {{ $data['education_detail'] }}</p>
			</div>
		</div>

		<div class="section">
			<h3>Occupation</h3>
			<div class="details">
				<p><strong>Occupation:</strong> {{ $data['occupation'] }}</p>
				<p><strong>Occupation Detail:</strong> {{ $data['occupation_detail'] }}</p>
			</div>
		</div>

		<div class="section">
			<h3>Physical Attributes</h3>
			<div class="details">
				<p><strong>Weight:</strong> {{ $data['weight'] }} kg</p>
				<p><strong>Height:</strong> {{ $data['height'] }}</p>
			</div>
		</div>

		<div class="section">
			<h3>Contact Information</h3>
			<div class="details">
				<p><strong>Phone:</strong> {{ $data['phone'] }}</p>
				<p><strong>Email:</strong> {{ $data['email'] }}</p>
			</div>
		</div>

		<div class="section">
			<h3>Additional Information</h3>
			<div class="details">
				<p><strong>Head of Family:</strong> {{ $data['is_hof'] ? 'Yes' : 'No' }}</p>
				<p><strong>Member Code:</strong> {{ $data['member_code'] }}</p>
				<p><strong>Family Code:</strong> {{ $data['family_code'] }}</p>
				<p><strong>Relation:</strong> {{ $data['relation'] }}</p>
			</div>
		</div>

		<div class="section">
			<h3>Address</h3>
			<div class="details">
				<p><strong>Address:</strong> {{ $data['address'] }}</p>
				<p><strong>City:</strong> {{ $data['city'] }}</p>
				<p><strong>State:</strong> {{ $data['state'] }}</p>
				<p><strong>Country:</strong> {{ $data['country'] }}</p>
			</div>
		</div>

		<div class="footer">
			<p>Generated on {{ now()->format('d-m-Y') }}</p>
		</div>
	</div>
</body>

</html>