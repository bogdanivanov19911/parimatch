						<live-block show-market="true" class="live-block-tag match" data-event="{name_1} - {name_2}" data-serial="{id}" data-time="{unix_start}">
							<div class="live-block-wrapper">
								<div class="live-block favactive ">
									<button type="button" class="live-block-fav "><i class="icon icon-ai-star-empty "></i></button>
									<button type="button" class="live-block-content">
										<div class="live-block-details">
										<div class="live-block-icons"></div>
										</div>
										<div class="live-block-names">
											<a href="/?do=event&id={id}">
												<div class="live-block-names__name "> {name_1} </div>
												<div class="live-block-names__name "> {name_2}  </div>
											</a>
										</div>
									</button> 
									<div class="live-block-more">
										<div class="live-block-more-info red-color"> {time_start} </div>
										<a href="/?do=event&id={id}">
											<div class="live-block-more-info"> +{more-result-count} <i class="icon icon-ai-angle-right"></i></div>
										</a>
									</div>
								</div>
								<main-markets class="main-market">
									<main-markets-group class="main-market-group">
									<div class="markets-group-block ">
										<span></span>
										<div riot-tag="outcome-group" class="included" sport-id="F">
											<div class="outcome-group-wrapper">
												<div riot-tag="outcome" market-type="2" class="outcome bet {bet-status-class}" data-finder="1X2" data-id="{bet_id}" data-name="Победитель матча:   {name_1}" data-result="1">
													<i class="icon icon-ai-caret-up"></i><i class="icon icon-ai-caret-down"></i>
													<div class="kef" data-limit="{limit_1}">{factor_1}</div>
												</div>
												<div riot-tag="outcome" market-type="2" class="outcome bet {bet-status-class}" data-finder="1X2" data-id="{bet_id}" data-name="Победитель матча:  Ничья" data-result="3">
													<i class="icon icon-ai-caret-up"></i><i class="icon icon-ai-caret-down"></i>
													<div class="kef" data-limit="{limit_3}">{factor_3}</div>
												</div>
												<div riot-tag="outcome" market-type="2" class="outcome bet {bet-status-class}" data-finder="1X2" data-id="{bet_id}" data-name="Победитель матча:  {name_2}" data-result="2">
													<i class="icon icon-ai-caret-up"></i><i class="icon icon-ai-caret-down"></i>
													<div class="kef" data-limit="{limit_2}">{factor_2}</div>
												</div>
											</div>
										</div>
									</div>
									</main-markets-group>
								</main-markets>
							</div>
						</live-block>