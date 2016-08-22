function [out, WS, BS] = ClustFit(x,Dpref,Dbelbin)

maxPrefScore = max(Dpref(:)); %Maximum score people can give each other
Nroles = size(Dbelbin,2); %Number of team roles
BelbinScores = unique(Dbelbin(:));
MaxTotalBelbin = sum(BelbinScores); 
nClust = max(x);

%Calculate within scatter based on preferences
WS = zeros(1,nClust); %Within scatter per cluster
BS = zeros(1,nClust); %Belbin scatter per cluster
for i = 1:nClust
    %%%%%% Preference score %%%%%%
    
    IinClust = x==i; %Elements in this cluster
    NinClust = sum(IinClust); %Count
    
    %Determine the maximum possible cluster score (best situation in which
    %everybody likes each other)
    maxClustScore = NinClust*sum(1:maxPrefScore);
    minClustScore = 0; %Minimum score: nobody likes each other
    
    %Select the submatrix for this cluster
    thisDpref = Dpref(IinClust,IinClust);
    
    %Count the scores for this cluster
    ClustScore = sum(sum(thisDpref(thisDpref~=0)));
    
    %Normalize between 0 and 1
    ClustScore = ClustScore - minClustScore;
    ClustScore = ClustScore/maxClustScore;
    
    %Mirror (want to minimize)
    ClustScore = 1-ClustScore;
    
    %Save
    WS(i) = ClustScore;
    
    
    %%%%%% Belbin score %%%%%%
    thisDbelbin = Dbelbin(IinClust,:);
    
    %Calculate sum of squared diffs wrt uniform distribution
    ClustScore = sum((sum(thisDbelbin,1)-(MaxTotalBelbin*NinClust/Nroles)).^2);
  
    %Maximum attainable score (worst): all members have the same role
    %distribution. ASSUME ONLY TOP 2 BELBIN SCORES ARE RELEVANT
    maxClustScore = (NinClust*BelbinScores(end)-(MaxTotalBelbin*NinClust/Nroles))^2 + ...
        (NinClust*BelbinScores(end-1)-(MaxTotalBelbin*NinClust/Nroles))^2 + ...
        (Nroles-2) * (0-(MaxTotalBelbin*NinClust/Nroles))^2;
    
    minClustScore = 0; %Minimum score: everything is uniform
  
    %Normalize between 0 and 1
    ClustScore = ClustScore - minClustScore;
    ClustScore = ClustScore/maxClustScore;

    BS(i) = ClustScore;
end

out = mean(WS) + mean(BS);