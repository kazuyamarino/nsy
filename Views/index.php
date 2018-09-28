<?php
	defined('ROOT') OR exit('No direct script access allowed');
?>
<div class="content">
	<header style="margin-top: 1rem;" class="grid-x grid-padding-x">
		<div class="large-9 cell">
			<h1>Welcome to NSY MVC Page</h1>
			<h4>NSY PHP Framework&nbsp;|&nbsp;<a href="<?php echo BASE_URL ?>hmvc">Go To HMVC Page</a></h4>
		</div>
		<div class="large-3 cell">
			<div class="text-center">
				<a target="_blank" href="https://bitbucket.org/kazuyamarino/nsy-framework"><i class="fab fa-bitbucket fa-5x"></i>
				<p>View On Bitbucket</p>
				</a>
			</div>
		</div>
	</header>

	<div class="grid-x grid-padding-x">
		<div class="large-12 cell">
			<div class="callout large">
				<h3>Hi, iam NSY!</h3>
				<p>NSY is a simple PHP Framework that works well on MVC or HMVC mode, its contain the <a target="_blank" href="https://html5boilerplate.com/">HTML5 Boilerplate</a> and <a target="_blank" href="http://foundation.zurb.com/">Foundation CSS Framework</a> in one package at a time. As well as include some support for <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/">Font-Awesome</a>. NSY also provides several optimizations for <a target="_blank" href="https://www.datatables.net/">Datatables</a> plugin.</p>

				<hr/>

				<h3>HTML5 Boilerplate! </h3>
				<p>HTML5 Boilerplate helps you build fast, robust, and adaptable web apps or sites. Kick-start your project with the combined knowledge and effort of 100s of developers, all in one little package.</p>
				<div class="row">
					<div class="large-4 medium-4 cell">
						<p><a target="_blank" href="https://github.com/h5bp/html5-boilerplate/blob/5.2.0/dist/doc/TOC.md">HTML5 Boilerplate Documentation</a><br />See documentation.</p>
					</div>
					<div class="large-4 medium-4 cell">
						<p><a target="_blank" href="https://github.com/h5bp/html5-boilerplate">HTML5 Boilerplate Source Code</a><br />The source code of the projects.</p>
					</div>
					<div class="large-4 medium-4 cell">
						<p><a target="_blank" href="https://h5bp.github.io/">Other Projects</a><br />See another projects.</p>
					</div>
				</div>

				<hr/>

				<h3>Foundation! </h3>
				<p>A <a target="_blank" href="http://foundation.zurb.com/learn/about.html">Framework</a> for any device, medium, and accessibility. Foundation is a family of responsive front-end frameworks that make it easy to design beautiful responsive websites, apps and emails that look amazing on any device. Foundation is semantic, readable, flexible, and completely customizable. We’re constantly adding new resources and <a target="_blank" href="http://foundation.zurb.com/develop/building-blocks.html">code snippets</a>, including these handy <a target="_blank" href="http://foundation.zurb.com/templates.html">HTML templates</a> to help get you started!</p>
				<div class="row">
					<div class="large-4 medium-4 cell">
						<p><a target="_blank" href="http://foundation.zurb.com/sites/docs/">Foundation Documentation</a><br />Everything you need to know about using the framework.</p>
					</div>
					<div class="large-4 medium-4 cell">
						<p><a target="_blank" href="http://zurb.com/university/code-skills">Foundation Code Skills</a><br />These online courses offer you a chance to better understand how Foundation works and how you can master it to create awesome projects.</p>
					</div>
					<div class="large-4 medium-4 cell">
						<p><a target="_blank" href="http://foundation.zurb.com/forum">Foundation Forum</a><br />Join the Foundation community to ask a question or show off your knowlege.</p>
					</div>
				</div>
				<div class="row">
					<div class="large-4 medium-4 medium-push-2 cell">
						<p><a target="_blank" href="http://github.com/zurb/foundation">Foundation on Github</a><br />Latest code, issue reports, feature requests and more.</p>
					</div>
					<div class="large-4 medium-4 medium-pull-2 cell">
						<p><a target="_blank" href="https://twitter.com/ZURBfoundation">@zurbfoundation</a><br />Ping us on Twitter if you have questions. When you build something with this we'd love to see it (and send you a totally boss sticker).</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="grid-x grid-padding-x">
		<div class="large-8 medium-8 cell">
			<h5>Here&rsquo;s your basic grid:</h5>

			<!-- Grid Example -->
			<div class="grid-x grid-padding-x">
				<div class="large-12 cell">
					<div class="callout ">
					<p><strong>This is a twelve column section in a row.</strong> Each of these includes a div.callout element so you can see where the columns are - it's not required at all for the grid.</p>
					</div>
				</div>
			</div>
			<div class="grid-x grid-padding-x">
				<div class="large-6 medium-6 cell">
					<div class="callout">
						<p>Six columns</p>
					</div>
				</div>
				<div class="large-6 medium-6 cell">
					<div class="callout">
						<p>Six columns</p>
					</div>
				</div>
			</div>
			<div class="grid-x grid-padding-x">
				<div class="large-4 medium-4 small-4 cell">
					<div class="callout">
						<p>Four columns</p>
					</div>
				</div>
				<div class="large-4 medium-4 small-4 cell">
					<div class="callout">
						<p>Four columns</p>
					</div>
				</div>
				<div class="large-4 medium-4 small-4 cell">
					<div class="callout">
						<p>Four columns</p>
					</div>
				</div>
			</div>
			<hr />
			<h5>Need form with validation example? Check it out bro!</h5>
			<form data-abide novalidate class="grid-x grid-padding-x">
				<div class="large-12 cell">
					<p class="help-text" id="exampleHelpText">Here's how you use this input field!</p>
					<div data-abide-error class="alert callout" style="display: none;">
						<p><i class="fi-alert"></i> There are some errors in your form.</p>
					</div>
					<label>Number Required
						<input type="text" placeholder="1234" aria-describedby="exampleHelpText" required pattern="^[-+]?\d*(?:[\.\,]\d+)?$">
						<span class="form-error">Yo, you had better fill this out, it's required.</span>
					</label>
					<label>Nothing Required!
						<input type="text" placeholder="Use me, or don't" aria-describedby="exampleHelpTex" required data-abide-ignore>
					</label>
					<p class="help-text" id="exampleHelpTex">This input is ignored by Abide using `data-abide-ignore`</p>
					<label>URL Pattern, not required, but throws error if it doesn't match the Regular Expression for a valid URL.
						<input type="text" placeholder="http://foundation.zurb.com" pattern="url">
					</label>
					<label>Password <small>required</small>
						<input type="password" id="password" required pattern="[a-zA-Z]+">
						<span class="form-error">Your password must match the requirements</span>
					</label>
					<label>Confirm Password <small>required</small>
						<input type="password" required pattern="[a-zA-Z]+" data-equalto="password">
						<span class="form-error">The password did not match</span>
					</label>
					<label>European Cars, Choose One, it can't be the blank option.
						<select id="select" required>
							<option value="">-- Choosen One --</option>
							<option value="volvo">Volvo</option>
							<option value="saab">Saab</option>
							<option value="mercedes">Mercedes</option>
							<option value="audi">Audi</option>
						</select>
					</label>

					<label for="smartphonesCheckbox">Pick at least two favs</label>
					<div id="smartphonesCheckbox" class="checkbox-group" data-validator-min="2" required>
						<input type="checkbox" name="smartphones" value="Hello Kitty"><label>Hello Kitty</label>
						<input type="checkbox" name="smartphones" value="My Melody"><label>My Melody</label>
						<input type="checkbox" name="smartphones" value="Dear Daniel"><label>Dear Daniel</label>
					</div>

					<label for="smartphonesRadio">Pick one color</label>
					<div id="smartphonesRadio" class="radio-group">
	        <input type="radio" name="pokemon" value="Red" id="pokemonRed" required><label for="pokemonRed">Red</label>
	        <input type="radio" name="pokemon" value="Blue" id="pokemonBlue"><label for="pokemonBlue">Blue</label>
	        <input type="radio" name="pokemon" value="Yellow" id="pokemonYellow"><label for="pokemonYellow">Yellow</label>
					</div>

					<div class="text-center">
					<button class="button" type="submit">Submit</button>
					<button class="button" type="reset">Reset</button></div>
				</div>
			</form>
		</div>

		<div class="large-4 medium-4 cell">
			<h5>Try one of these buttons:</h5>
			<p><a href="#" class="small button">Simple Button</a><br/>
			<a href="#" class="medium success button">Success Button</a><br/>
			<a href="#" class="medium alert button">Alert Button</a><br/>
			<a href="#" class="medium secondary button">Secondary Button</a></p>
			<div class="callout">
				<h5>So many components, girl!</h5>
				<p>A whole kitchen sink of goodies comes with Foundation. Check out the docs to see them all, along with details on making them your own.</p>
				<a target="_blank" href="http://foundation.zurb.com/docs/" class="small button">Go to Foundation Docs</a>
			</div>
			<div class="callout">
				<h5>You'll like this tooltip!</h5>
				<p>The <span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover='false' tabindex=1 title="Fancy word for a beetle.">scarabaeus</span> hung quite clear of any branches, and, if allowed to fall, would have fallen at our feet. Legrand immediately took the scythe, and cleared with it a circular space, three or four yards in diameter, just beneath the <span data-tooltip aria-haspopup="true" class="has-tip left" data-disable-hover="false" tabindex="3" title="Need pesticides">insect</span>, and, having accomplished this, ordered <span data-tooltip aria-haspopup="true" class="has-tip right" data-disable-hover="false" tabindex="3" title="Big planet">Jupiter</span> to let go the string and come down from the tree.</p>
			</div>
			<h5>The awesome Modal/Reveal!</h5>
			<p><a data-open="exampleModal1">Click me for a modal</a></p>
			<div class="small reveal" id="exampleModal1" data-reveal>
				<h1>Awesome. I Have It.</h1>
				<p class="lead">Your couch. It is mine.</p>
				<p>I'm a cool paragraph that lives inside of an even cooler modal. Wins!</p>
				<button class="close-button" data-close aria-label="Close reveal" type="button">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="alert callout" data-closable>
			  <h5>This is Important!</h5>
			  <p>But when you're done reading it, click the close button in the corner to dismiss this alert.</p>
			  <button class="close-button" aria-label="Close alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
		</div>
	</div>

	<div class="grid-x grid-padding-x">
		<hr/>
		<div class="large-12 medium-12 cell">
			<h5>Here is DataTables Examples!</h5>
			<table id="example" class="display responsive" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>Position</th>
						<th>Office</th>
						<th>Age</th>
						<th>Start date</th>
						<th>Salary</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Tiger Nixon</td>
						<td>System Architect</td>
						<td>Edinburgh</td>
						<td>61</td>
						<td>2011/04/25</td>
						<td>$320,800</td>
					</tr>
					<tr>
						<td>Garrett Winters</td>
						<td>Accountant</td>
						<td>Tokyo</td>
						<td>63</td>
						<td>2011/07/25</td>
						<td>$170,750</td>
					</tr>
					<tr>
						<td>Ashton Cox</td>
						<td>Junior Technical Author</td>
						<td>San Francisco</td>
						<td>66</td>
						<td>2009/01/12</td>
						<td>$86,000</td>
					</tr>
					<tr>
						<td>Cedric Kelly</td>
						<td>Senior Javascript Developer</td>
						<td>Edinburgh</td>
						<td>22</td>
						<td>2012/03/29</td>
						<td>$433,060</td>
					</tr>
					<tr>
						<td>Airi Satou</td>
						<td>Accountant</td>
						<td>Tokyo</td>
						<td>33</td>
						<td>2008/11/28</td>
						<td>$162,700</td>
					</tr>
					<tr>
						<td>Brielle Williamson</td>
						<td>Integration Specialist</td>
						<td>New York</td>
						<td>61</td>
						<td>2012/12/02</td>
						<td>$372,000</td>
					</tr>
					<tr>
						<td>Herrod Chandler</td>
						<td>Sales Assistant</td>
						<td>San Francisco</td>
						<td>59</td>
						<td>2012/08/06</td>
						<td>$137,500</td>
					</tr>
					<tr>
						<td>Rhona Davidson</td>
						<td>Integration Specialist</td>
						<td>Tokyo</td>
						<td>55</td>
						<td>2010/10/14</td>
						<td>$327,900</td>
					</tr>
					<tr>
						<td>Colleen Hurst</td>
						<td>Javascript Developer</td>
						<td>San Francisco</td>
						<td>39</td>
						<td>2009/09/15</td>
						<td>$205,500</td>
					</tr>
					<tr>
						<td>Sonya Frost</td>
						<td>Software Engineer</td>
						<td>Edinburgh</td>
						<td>23</td>
						<td>2008/12/13</td>
						<td>$103,600</td>
					</tr>
					<tr>
						<td>Jena Gaines</td>
						<td>Office Manager</td>
						<td>London</td>
						<td>30</td>
						<td>2008/12/19</td>
						<td>$90,560</td>
					</tr>
					<tr>
						<td>Quinn Flynn</td>
						<td>Support Lead</td>
						<td>Edinburgh</td>
						<td>22</td>
						<td>2013/03/03</td>
						<td>$342,000</td>
					</tr>
					<tr>
						<td>Charde Marshall</td>
						<td>Regional Director</td>
						<td>San Francisco</td>
						<td>36</td>
						<td>2008/10/16</td>
						<td>$470,600</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Footer that is hidden before 'sticky' is ready and appears at the bottom of the page onload -->
<footer class="footer">
	<div style="margin-bottom:1rem;" class="grid-x grid-padding-x">
		<hr />
		<div class="large-12 cell">
			<div class="grid-x grid-padding-x">
				<div class="large-5 medium-5 cell">
					<p><i class="fab fa-html5 fa-3x"></i>&nbsp;<i class="fab fa-css3 fa-3x"></i></p>
					<p>This is a Sticky Footer.</p>
					<p><a target="_blank" href="mailto:admin@kazuyamarino.com">Vikry Yuansah </a><i class="fas fa-forward"></i> <a href="<?php echo BASE_URL ?>">NSY 2015 - <?php echo date("Y"); ?></a></p>
					<hr class="show-for-small-only"/>
				</div>
				<div class="large-3 medium-2 cell">
					<p><strong><a target="_blank" href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a></strong> is The iconic font and CSS toolkit</p>
					<div class="list-group">
						<i class="fab fa-twitter fa-2x fa-fw"></i><a class="list-group-item" target="_blank" href="https://twitter.com/abang_marino">&nbsp; Twitter</a></br>
						<i class="fab fa-facebook fa-2x fa-fw"></i><a class="list-group-item" target="_blank" href="https://www.facebook.com/kazuya.marino">&nbsp; Facebook</a></br>
						<i class="fab fa-linkedin fa-2x fa-fw"></i><a class="list-group-item" target="_blank" href="https://id.linkedin.com/in/vikry-yuansyah-1265a4a7">&nbsp; Linkedin</a></br>
						<i class="fas fa-envelope fa-2x fa-fw"></i><a class="list-group-item" href="mailto:admin@kazuyamarino.com">&nbsp; Email</a>
					</div>
					<hr class="show-for-small-only"/>
				</div>
				<div class="large-4 medium-3 cell">
					<p>Newsletter:</p>
					<p><input type="text" value="Email Address"><input type="submit" class="button radius right"></p>
				</div>
			</div>
		</div>
	</div>
</footer>
